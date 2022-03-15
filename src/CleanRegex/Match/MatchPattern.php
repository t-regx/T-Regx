<?php
namespace TRegx\CleanRegex\Match;

use TRegx\CleanRegex\Exception\NoSuchNthElementException;
use TRegx\CleanRegex\Exception\SubjectNotMatchedException;
use TRegx\CleanRegex\Internal\Definition;
use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\Match\Base\ApiBase;
use TRegx\CleanRegex\Internal\Match\Base\Base;
use TRegx\CleanRegex\Internal\Match\FlatFunction;
use TRegx\CleanRegex\Internal\Match\FlatMap\ArrayMergeStrategy;
use TRegx\CleanRegex\Internal\Match\FlatMap\AssignStrategy;
use TRegx\CleanRegex\Internal\Match\IntStream\MatchIntMessages;
use TRegx\CleanRegex\Internal\Match\IntStream\MatchOffsetMessages;
use TRegx\CleanRegex\Internal\Match\IntStream\NthIntStreamElement;
use TRegx\CleanRegex\Internal\Match\MatchAll\LazyMatchAllFactory;
use TRegx\CleanRegex\Internal\Match\MatchAll\MatchAllFactory;
use TRegx\CleanRegex\Internal\Match\MatchFirst;
use TRegx\CleanRegex\Internal\Match\MatchOnly;
use TRegx\CleanRegex\Internal\Match\PresentOptional;
use TRegx\CleanRegex\Internal\Match\Stream\Base\MatchIntStream;
use TRegx\CleanRegex\Internal\Match\Stream\Base\MatchStream;
use TRegx\CleanRegex\Internal\Match\Stream\Base\OffsetLimitStream;
use TRegx\CleanRegex\Internal\Match\Stream\Base\StreamBase;
use TRegx\CleanRegex\Internal\Match\UserData;
use TRegx\CleanRegex\Internal\MatchPatternHelpers;
use TRegx\CleanRegex\Internal\Message\SubjectNotMatched\FirstMatchMessage;
use TRegx\CleanRegex\Internal\Model\DetailObjectFactory;
use TRegx\CleanRegex\Internal\Model\FalseNegative;
use TRegx\CleanRegex\Internal\Model\GroupAware;
use TRegx\CleanRegex\Internal\Model\GroupPolyfillDecorator;
use TRegx\CleanRegex\Internal\Model\LightweightGroupAware;
use TRegx\CleanRegex\Internal\Model\Match\RawMatchOffset;
use TRegx\CleanRegex\Internal\Numeral;
use TRegx\CleanRegex\Internal\Predicate;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\CleanRegex\Internal\SubjectEmptyOptional;
use TRegx\CleanRegex\Match\Details\Detail;
use TRegx\CleanRegex\Match\Details\MatchDetail;
use TRegx\SafeRegex\preg;

class MatchPattern implements \Countable, \IteratorAggregate
{
    use MatchPatternHelpers;

    /** @var Definition */
    private $definition;
    /** @var Subject */
    private $subject;
    /** @var Base */
    private $base;
    /** @var GroupAware */
    private $groupAware;
    /** @var MatchAllFactory */
    private $allFactory;
    /** @var MatchOnly */
    private $matchOnly;
    /** @var UserData */
    private $userData;

    public function __construct(Definition $definition, Subject $subject)
    {
        $this->definition = $definition;
        $this->subject = $subject;
        $this->base = new ApiBase($definition, $subject);
        $this->groupAware = new LightweightGroupAware($definition);
        $this->allFactory = new LazyMatchAllFactory($this->base);
        $this->matchOnly = new MatchOnly($definition, $this->base);
        $this->userData = new UserData();
    }

    public function test(): bool
    {
        return preg::match($this->definition->pattern, $this->subject) === 1;
    }

    public function fails(): bool
    {
        return !$this->test();
    }

    public function all(): array
    {
        return \array_values($this->base->matchAll()->getTexts());
    }

    /**
     * @param null|callable $consumer
     * @return string|mixed
     * @throws SubjectNotMatchedException
     */
    public function first(callable $consumer = null)
    {
        $first = new MatchFirst($this->base, $this->subject, $this->userData, $this->allFactory);
        if ($consumer === null) {
            return $first->matchDetails()->text();
        }
        return $consumer($first->matchDetails());
    }

    public function findFirst(callable $consumer): Optional
    {
        $match = $this->base->matchOffset();
        if ($match->matched()) {
            return new PresentOptional($consumer($this->findFirstDetail($match)));
        }
        return new SubjectEmptyOptional($this->groupAware, $this->subject, new FirstMatchMessage());
    }

    private function findFirstDetail(RawMatchOffset $match): Detail
    {
        $polyfill = new GroupPolyfillDecorator(new FalseNegative($match), $this->allFactory, 0);
        return MatchDetail::create($this->subject, 0, 1, $polyfill, $this->allFactory, $this->userData);
    }

    public function only(int $limit): array
    {
        return $this->matchOnly->get($limit);
    }

    public function nth(int $index): string
    {
        if ($index < 0) {
            throw new \InvalidArgumentException("Negative nth: $index");
        }
        $texts = \array_values($this->base->matchAll()->getTexts());
        if (\array_key_exists($index, $texts)) {
            return $texts[$index];
        }
        if (empty($texts)) {
            throw SubjectNotMatchedException::forNth($this->subject, $index);
        }
        throw NoSuchNthElementException::forSubject($index, \count($texts));
    }

    public function forEach(callable $consumer): void
    {
        foreach ($this as $index => $object) {
            $consumer($object, $index);
        }
    }

    public function map(callable $mapper): array
    {
        return \array_values(\array_map($mapper, $this->getDetailObjects()));
    }

    public function filter(callable $predicate): array
    {
        return \array_values(\array_map(static function (Detail $detail): string {
            return $detail->text();
        }, \array_filter($this->getDetailObjects(), [new Predicate($predicate, 'filter'), 'test'])));
    }

    public function flatMap(callable $mapper): array
    {
        $function = new FlatFunction($mapper, 'flatMap');
        return (new ArrayMergeStrategy())->flatten($function->map($this->getDetailObjects()));
    }

    public function flatMapAssoc(callable $mapper): array
    {
        $function = new FlatFunction($mapper, 'flatMapAssoc');
        return (new AssignStrategy())->flatten($function->map($this->getDetailObjects()));
    }

    public function distinct(): array
    {
        return \array_values(\array_unique($this->all()));
    }

    /**
     * @param string|int $nameOrIndex
     * @return GroupLimit
     */
    public function group($nameOrIndex): GroupLimit
    {
        return new GroupLimit($this->base, $this->subject, $this->groupAware, GroupKey::of($nameOrIndex));
    }

    public function offsets(): IntStream
    {
        $upstream = new OffsetLimitStream($this->base, $this->subject);
        return new IntStream($upstream, new NthIntStreamElement($upstream, $this->subject, new MatchOffsetMessages()), $this->subject);
    }

    public function count(): int
    {
        return preg::match_all($this->definition->pattern, $this->subject);
    }

    public function getIterator(): \Iterator
    {
        return new \ArrayIterator(\array_values($this->getDetailObjects()));
    }

    public function stream(): Stream
    {
        return new Stream(new MatchStream(new StreamBase($this->base), $this->subject, $this->userData, new LazyMatchAllFactory($this->base)), $this->subject);
    }

    public function asInt(int $base = null): IntStream
    {
        $upstream = new MatchIntStream(new StreamBase($this->base), new Numeral\Base($base), $this->subject);
        return new IntStream($upstream, new NthIntStreamElement($upstream, $this->subject, new MatchIntMessages()), $this->subject);
    }

    /**
     * @param string|int $nameOrIndex
     * @return GroupByPattern
     */
    public function groupBy($nameOrIndex): GroupByPattern
    {
        return new GroupByPattern($this->base, $this->subject, $this->userData, $this->groupAware, GroupKey::of($nameOrIndex));
    }

    public function groupByCallback(callable $groupMapper): array
    {
        $result = [];
        foreach ($this as $detail) {
            $key = $groupMapper($detail);
            $result[$key][] = $detail->text();
        }
        return $result;
    }

    private function getDetailObjects(): array
    {
        $factory = new DetailObjectFactory($this->subject, $this->userData);
        return $factory->mapToDetailObjects($this->base->matchAllOffsets());
    }

    public function reduce(callable $reducer, $accumulator)
    {
        foreach ($this as $detail) {
            $accumulator = $reducer($accumulator, $detail);
        };
        return $accumulator;
    }
}

<?php
namespace TRegx\CleanRegex\Match;

use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\CleanRegex\Exception\NonexistentGroupException;
use TRegx\CleanRegex\Exception\NoSuchNthElementException;
use TRegx\CleanRegex\Exception\SubjectNotMatchedException;
use TRegx\CleanRegex\Internal\Definition;
use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\GroupNames;
use TRegx\CleanRegex\Internal\Match\FlatFunction;
use TRegx\CleanRegex\Internal\Match\FlatMap\ArrayMergeStrategy;
use TRegx\CleanRegex\Internal\Match\FlatMap\AssignStrategy;
use TRegx\CleanRegex\Internal\Match\GroupByFunction;
use TRegx\CleanRegex\Internal\Match\IntStream\MatchIntMessages;
use TRegx\CleanRegex\Internal\Match\IntStream\NthIntStreamElement;
use TRegx\CleanRegex\Internal\Match\MatchOnly;
use TRegx\CleanRegex\Internal\Match\PresentOptional;
use TRegx\CleanRegex\Internal\Match\Stream\Base\MatchIntStream;
use TRegx\CleanRegex\Internal\Match\Stream\Base\MatchStream;
use TRegx\CleanRegex\Internal\Match\Stream\Base\StreamBase;
use TRegx\CleanRegex\Internal\MatchPatternHelpers;
use TRegx\CleanRegex\Internal\Message\SubjectNotMatched\FirstMatchMessage;
use TRegx\CleanRegex\Internal\Model\DetailObjectFactory;
use TRegx\CleanRegex\Internal\Model\FalseNegative;
use TRegx\CleanRegex\Internal\Model\GroupAware;
use TRegx\CleanRegex\Internal\Model\LightweightGroupAware;
use TRegx\CleanRegex\Internal\Numeral;
use TRegx\CleanRegex\Internal\Pcre\DeprecatedMatchDetail;
use TRegx\CleanRegex\Internal\Pcre\Legacy\ApiBase;
use TRegx\CleanRegex\Internal\Pcre\Legacy\Base;
use TRegx\CleanRegex\Internal\Pcre\Legacy\GroupPolyfillDecorator;
use TRegx\CleanRegex\Internal\Pcre\Legacy\LazyMatchAllFactory;
use TRegx\CleanRegex\Internal\Pcre\Legacy\MatchAllFactory;
use TRegx\CleanRegex\Internal\Pcre\Legacy\Prime\MatchPrime;
use TRegx\CleanRegex\Internal\Pcre\Legacy\RawMatchOffset;
use TRegx\CleanRegex\Internal\Predicate;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\CleanRegex\Internal\SubjectEmptyOptional;
use TRegx\CleanRegex\Match\Details\Detail;
use TRegx\CleanRegex\Match\Details\Structure;
use TRegx\SafeRegex\preg;

class MatchPattern implements \Countable, \IteratorAggregate, Structure
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
    /** @var GroupNames */
    private $groupNames;

    public function __construct(Definition $definition, Subject $subject)
    {
        $this->definition = $definition;
        $this->subject = $subject;
        $this->base = new ApiBase($definition, $subject);
        $this->groupAware = new LightweightGroupAware($definition);
        $this->allFactory = new LazyMatchAllFactory($this->base);
        $this->matchOnly = new MatchOnly($definition, $this->base);
        $this->groupNames = new GroupNames($this->groupAware);
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
        return $this->base->matchAll()->getTexts();
    }

    /**
     * @param null|callable $consumer
     * @return string|mixed
     */
    public function first(callable $consumer = null)
    {
        if ($consumer === null) {
            return $this->matchDetail()->text();
        }
        return $consumer($this->matchDetail());
    }

    public function findFirst(callable $consumer): Optional
    {
        $match = $this->base->matchOffset();
        if ($match->matched()) {
            return new PresentOptional($consumer($this->findFirstDetail($match)));
        }
        return new SubjectEmptyOptional($this->subject, new FirstMatchMessage());
    }

    private function matchDetail(): Detail
    {
        $match = $this->base->matchOffset();
        if ($match->matched()) {
            return $this->findFirstDetail($match);
        }
        throw new SubjectNotMatchedException(new FirstMatchMessage(), $this->subject);
    }

    private function findFirstDetail(RawMatchOffset $match): Detail
    {
        $polyfill = new GroupPolyfillDecorator(new FalseNegative($match), $this->allFactory, 0, $this->groupAware);
        return DeprecatedMatchDetail::create($this->subject, 0, $polyfill, $this->allFactory, new MatchPrime($match));
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
     * @return GroupMatch
     * @deprecated Will be removed in the next release
     */
    public function group($nameOrIndex): GroupMatch
    {
        return new GroupMatch($this->base, $this->subject, $this->groupAware, GroupKey::of($nameOrIndex));
    }

    public function count(): int
    {
        return preg::match_all($this->definition->pattern, $this->subject);
    }

    public function getIterator(): \Iterator
    {
        return new \ArrayIterator($this->getDetailObjects());
    }

    public function stream(): Stream
    {
        return new Stream(new MatchStream(new StreamBase($this->base), $this->subject, $this->allFactory, $this->groupAware));
    }

    public function asInt(int $base = 10): IntStream
    {
        $upstream = new MatchIntStream(new StreamBase($this->base), new Numeral\Base($base), $this->subject);
        return new IntStream($upstream, new NthIntStreamElement($upstream, $this->subject, new MatchIntMessages()));
    }

    /**
     * @param string|int $nameOrIndex
     * @return array
     */
    public function groupBy($nameOrIndex): array
    {
        $group = GroupKey::of($nameOrIndex);
        if (!$this->groupAware->hasGroup($group)) {
            throw new NonexistentGroupException($group);
        }
        $map = [];
        $factory = new DetailObjectFactory($this->subject);
        $matches = $this->base->matchAllOffsets();
        foreach ($matches->getIndexes() as $index) {
            if ($matches->isGroupMatched($group->nameOrIndex(), $index)) {
                [$text] = $matches->getGroupTextAndOffset($group->nameOrIndex(), $index);
                $map[$text][] = $factory->mapToDetailObject($matches, $index);
            } else {
                throw GroupNotMatchedException::forGroupBy($group);
            }
        }
        return $map;
    }

    public function groupByCallback(callable $groupMapper): array
    {
        return $this->groupped(new GroupByFunction('groupByCallback', $groupMapper));
    }

    private function groupped(GroupByFunction $function): array
    {
        $result = [];
        foreach ($this as $detail) {
            $result[$function->apply($detail)][] = $detail->text();
        }
        return $result;
    }

    private function getDetailObjects(): array
    {
        $factory = new DetailObjectFactory($this->subject);
        return $factory->mapToDetailObjects($this->base->matchAllOffsets());
    }

    public function reduce(callable $reducer, $accumulator)
    {
        foreach ($this as $detail) {
            $accumulator = $reducer($accumulator, $detail);
        }
        return $accumulator;
    }

    /**
     * @return string[]
     */
    public function groupNames(): array
    {
        return $this->groupNames->groupNames();
    }

    public function groupsCount(): int
    {
        preg::match_all($this->definition->pattern, '', $matches);
        return \count(\array_filter(\array_keys($matches), '\is_int')) - 1;
    }

    /**
     * @param string|int $nameOrIndex
     * @return bool
     */
    public function groupExists($nameOrIndex): bool
    {
        return $this->groupAware->hasGroup(GroupKey::of($nameOrIndex));
    }

    public function subject(): string
    {
        return $this->subject->asString();
    }
}

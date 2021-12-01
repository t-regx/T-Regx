<?php
namespace TRegx\CleanRegex\Match;

use TRegx\CleanRegex\Exception\NoSuchNthElementException;
use TRegx\CleanRegex\Exception\SubjectNotMatchedException;
use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\Match\Base\ApiBase;
use TRegx\CleanRegex\Internal\Match\Base\Base;
use TRegx\CleanRegex\Internal\Match\Base\DetailPredicateBaseDecorator;
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
use TRegx\CleanRegex\Internal\Match\MethodPredicate;
use TRegx\CleanRegex\Internal\Match\PresentOptional;
use TRegx\CleanRegex\Internal\Match\Stream\Base\MatchIntStream;
use TRegx\CleanRegex\Internal\Match\Stream\Base\MatchStream;
use TRegx\CleanRegex\Internal\Match\Stream\Base\OffsetLimitStream;
use TRegx\CleanRegex\Internal\Match\Stream\Base\StreamBase;
use TRegx\CleanRegex\Internal\MatchPatternHelpers;
use TRegx\CleanRegex\Internal\Message\SubjectNotMatched\FirstMatchMessage;
use TRegx\CleanRegex\Internal\Model\DetailObjectFactory;
use TRegx\CleanRegex\Internal\Model\FalseNegative;
use TRegx\CleanRegex\Internal\Model\GroupAware;
use TRegx\CleanRegex\Internal\Model\GroupPolyfillDecorator;
use TRegx\CleanRegex\Internal\Model\LightweightGroupAware;
use TRegx\CleanRegex\Internal\Model\Match\RawMatchOffset;
use TRegx\CleanRegex\Internal\Number;
use TRegx\CleanRegex\Internal\Predicate;
use TRegx\CleanRegex\Internal\SubjectEmptyOptional;
use TRegx\CleanRegex\Match\Details\Detail;
use TRegx\CleanRegex\Match\Details\MatchDetail;

class RemainingMatchPattern implements \IteratorAggregate
{
    use MatchPatternHelpers;

    /** @var ApiBase */
    private $originalBase;
    /** @var MatchAllFactory */
    private $allFactory;
    /** @var Base */
    protected $base;
    /** @var GroupAware */
    private $groupAware;
    /** @var MatchOnly */
    private $matchOnly;

    public function __construct(DetailPredicateBaseDecorator $base, Base $original, MatchAllFactory $allFactory)
    {
        $this->originalBase = $original;
        $this->allFactory = $allFactory;
        $this->base = $base;
        $this->groupAware = new LightweightGroupAware($this->base->definition());
        $this->matchOnly = new MatchOnly($this->base);
    }

    public function remaining(callable $predicate): RemainingMatchPattern
    {
        return new RemainingMatchPattern(
            new DetailPredicateBaseDecorator($this->base, new MethodPredicate($predicate, 'remaining')),
            $this->originalBase,
            $this->allFactory);
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
        $first = new MatchFirst($this->base, $this->allFactory);
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
        return new SubjectEmptyOptional($this->groupAware, $this->base, new FirstMatchMessage());
    }

    private function findFirstDetail(RawMatchOffset $match): Detail
    {
        $firstIndex = $match->getIndex();
        $polyfill = new GroupPolyfillDecorator(new FalseNegative($match), $this->allFactory, $firstIndex);
        return MatchDetail::create($this->base, $firstIndex, 1, $polyfill, $this->allFactory, $this->base->getUserData());
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
            throw SubjectNotMatchedException::forNth($this->base, $index);
        }
        throw NoSuchNthElementException::forSubject($index, \count($texts));
    }

    public function filter(callable $predicate): array
    {
        return \array_values(\array_map(static function (Detail $detail): string {
            return $detail->text();
        }, \array_filter($this->getDetailObjects(), [new Predicate($predicate, 'filter'), 'test'])));
    }

    public function flatMap(callable $mapper): array
    {
        $function = new FlatFunction($mapper, 'flatMap');;
        return (new ArrayMergeStrategy())->flatten($function->map($this->getDetailObjects()));
    }

    public function flatMapAssoc(callable $mapper): array
    {
        $function = new FlatFunction($mapper, 'flatMapAssoc');;
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
        return new GroupLimit($this->base, $this->groupAware, GroupKey::of($nameOrIndex));
    }

    public function offsets(): IntStream
    {
        $upstream = new OffsetLimitStream($this->base);
        return new IntStream($upstream, new NthIntStreamElement($upstream, $this->base, new MatchOffsetMessages()), $this->base);
    }

    public function getIterator(): \Iterator
    {
        return new \ArrayIterator(\array_values($this->getDetailObjects()));
    }

    public function stream(): Stream
    {
        return new Stream(new MatchStream(new StreamBase($this->base), $this->base, $this->base->getUserData(), new LazyMatchAllFactory($this->base)), $this->base);
    }

    public function asInt(int $base = null): IntStream
    {
        $upstream = new MatchIntStream(new StreamBase($this->base), new Number\Base($base), $this->base);
        return new IntStream($upstream, new NthIntStreamElement($upstream, $this->base, new MatchIntMessages()), $this->base);
    }

    /**
     * @param string|int $nameOrIndex
     * @return GroupByPattern
     */
    public function groupBy($nameOrIndex): GroupByPattern
    {
        return new GroupByPattern($this->base, GroupKey::of($nameOrIndex));
    }

    /**
     * @return Detail[]
     */
    protected function getDetailObjects(): array
    {
        $factory = new DetailObjectFactory($this->base, $this->base->getUserData());
        return $factory->mapToDetailObjects($this->base->matchAllOffsets());
    }
}

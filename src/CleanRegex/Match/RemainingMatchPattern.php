<?php
namespace TRegx\CleanRegex\Match;

use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\Match\Base\ApiBase;
use TRegx\CleanRegex\Internal\Match\Base\Base;
use TRegx\CleanRegex\Internal\Match\Base\DetailPredicateBaseDecorator;
use TRegx\CleanRegex\Internal\Match\IntStream\MatchIntMessages;
use TRegx\CleanRegex\Internal\Match\IntStream\MatchOffsetMessages;
use TRegx\CleanRegex\Internal\Match\IntStream\NthIntStreamElement;
use TRegx\CleanRegex\Internal\Match\MatchAll\MatchAllFactory;
use TRegx\CleanRegex\Internal\Match\MatchOnly;
use TRegx\CleanRegex\Internal\Match\MethodPredicate;
use TRegx\CleanRegex\Internal\Match\Stream\Base\MatchIntStream;
use TRegx\CleanRegex\Internal\Match\Stream\Base\OffsetLimitStream;
use TRegx\CleanRegex\Internal\Match\Stream\Base\StreamBase;
use TRegx\CleanRegex\Internal\Model\DetailObjectFactory;
use TRegx\CleanRegex\Internal\Model\GroupAware;
use TRegx\CleanRegex\Internal\Model\LightweightGroupAware;
use TRegx\CleanRegex\Internal\Number;
use TRegx\CleanRegex\Match\Details\Detail;

class RemainingMatchPattern implements \IteratorAggregate
{
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

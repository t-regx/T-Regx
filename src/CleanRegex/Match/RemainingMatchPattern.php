<?php
namespace TRegx\CleanRegex\Match;

use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\Match\Base\ApiBase;
use TRegx\CleanRegex\Internal\Match\Base\Base;
use TRegx\CleanRegex\Internal\Match\Base\DetailPredicateBaseDecorator;
use TRegx\CleanRegex\Internal\Match\MatchAll\MatchAllFactory;
use TRegx\CleanRegex\Internal\Match\MethodPredicate;
use TRegx\CleanRegex\Internal\Model\GroupAware;
use TRegx\CleanRegex\Internal\Model\LightweightGroupAware;

class RemainingMatchPattern
{
    /** @var ApiBase */
    private $originalBase;
    /** @var MatchAllFactory */
    private $allFactory;
    /** @var Base */
    protected $base;
    /** @var GroupAware */
    private $groupAware;

    public function __construct(DetailPredicateBaseDecorator $base, Base $original, MatchAllFactory $allFactory)
    {
        $this->originalBase = $original;
        $this->allFactory = $allFactory;
        $this->base = $base;
        $this->groupAware = new LightweightGroupAware($this->base->definition());
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

    /**
     * @param string|int $nameOrIndex
     * @return GroupByPattern
     */
    public function groupBy($nameOrIndex): GroupByPattern
    {
        return new GroupByPattern($this->base, GroupKey::of($nameOrIndex));
    }
}

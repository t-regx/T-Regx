<?php
namespace TRegx\CleanRegex\Match;

use ArrayIterator;
use InvalidArgumentException;
use Iterator;
use TRegx\CleanRegex\Internal\Match\Base\Base;
use TRegx\CleanRegex\Internal\Match\Details\Group\GroupFacade;
use TRegx\CleanRegex\Internal\Match\Details\Group\MatchGroupFactoryStrategy;
use TRegx\CleanRegex\Internal\Match\FlatMapper;
use TRegx\CleanRegex\Internal\Match\MatchAll\EagerMatchAllFactory;
use TRegx\CleanRegex\Internal\Match\MatchAll\LazyMatchAllFactory;
use TRegx\CleanRegex\Internal\Model\Match\IRawMatchOffset;
use TRegx\CleanRegex\Internal\Model\Matches\IRawMatches;
use TRegx\CleanRegex\Internal\Model\Matches\IRawMatchesOffset;
use TRegx\CleanRegex\Internal\OffsetLimit\MatchOffsetLimitFactory;
use TRegx\CleanRegex\Internal\PatternLimit;
use TRegx\CleanRegex\Match\Details\Group\MatchGroup;
use TRegx\CleanRegex\Match\Offset\OffsetLimit;

class GroupLimit implements PatternLimit
{
    /** @var callable */
    private $allFactory;
    /** @var callable */
    private $firstFactory;
    /** @var MatchOffsetLimitFactory */
    private $offsetLimitFactory;
    /** @var Base */
    private $base;
    /** @var string|int */
    private $nameOrIndex;

    public function __construct(callable $allFactory, callable $firstFactory, MatchOffsetLimitFactory $offsetLimitFactory, Base $base, $nameOrIndex)
    {
        $this->allFactory = $allFactory;
        $this->firstFactory = $firstFactory;
        $this->offsetLimitFactory = $offsetLimitFactory;
        $this->base = $base;
        $this->nameOrIndex = $nameOrIndex;
    }

    public function offsets(): OffsetLimit
    {
        return $this->offsetLimitFactory->create();
    }

    /**
     * @param callable|null $consumer
     * @return string|mixed
     */
    public function first(callable $consumer = null)
    {
        /** @var IRawMatchOffset $first */
        $first = \call_user_func($this->firstFactory);
        if ($consumer === null) {
            return $first->getGroup($this->nameOrIndex);
        }
        $matchGroup = (new GroupFacade($first, $this->base, $this->nameOrIndex, new MatchGroupFactoryStrategy(), new LazyMatchAllFactory($this->base)))->createGroup($first);
        return $consumer($matchGroup);
    }

    /**
     * @return (string|null)[]
     */
    public function all(): array
    {
        /** @var IRawMatches $rawMatches */
        $rawMatches = \call_user_func($this->allFactory);
        return $rawMatches->getGroupTexts($this->nameOrIndex);
    }

    /**
     * @param int $limit
     * @return (string|null)[]
     */
    public function only(int $limit): array
    {
        /** @var IRawMatches $rawMatches */
        $matches = \call_user_func($this->allFactory);
        if ($limit < 0) {
            throw new InvalidArgumentException("Negative limit $limit");
        }
        return \array_slice($matches->getGroupTexts($this->nameOrIndex), 0, $limit);
    }

    public function iterator(): Iterator
    {
        return new ArrayIterator($this->all());
    }

    /**
     * @param callable $mapper
     * @return mixed[]
     */
    public function map(callable $mapper): array
    {
        return \array_map($mapper, $this->getMatchGroupObjects());
    }

    /**
     * @param callable $mapper
     * @return mixed[]
     */
    public function flatMap(callable $mapper): array
    {
        return (new FlatMapper($this->getMatchGroupObjects(), $mapper))->get();
    }

    public function forEach(callable $consumer): void
    {
        foreach ($this->getMatchGroupObjects() as $group) {
            $consumer($group);
        }
    }

    public function iterate(callable $consumer): void
    {
        $this->forEach($consumer);
    }

    /**
     * @return MatchGroup[]
     */
    private function getMatchGroupObjects()
    {
        /** @var IRawMatchesOffset $rawMatches */
        $rawMatches = \call_user_func($this->allFactory);
        return (new GroupFacade($rawMatches, $this->base, $this->nameOrIndex, new MatchGroupFactoryStrategy(), new EagerMatchAllFactory($rawMatches)))->createGroups($rawMatches);
    }
}

<?php
namespace TRegx\CleanRegex\Match;

use ArrayIterator;
use InvalidArgumentException;
use Iterator;
use TRegx\CleanRegex\Internal\Match\Details\Group\GroupFacade;
use TRegx\CleanRegex\Internal\Match\Details\Group\MatchGroupFactoryStrategy;
use TRegx\CleanRegex\Internal\Match\MatchAll\EagerMatchAllFactory;
use TRegx\CleanRegex\Internal\Model\Matches\IRawMatches;
use TRegx\CleanRegex\Internal\Model\Matches\IRawMatchesOffset;
use TRegx\CleanRegex\Internal\OffsetLimit\MatchOffsetLimitFactory;
use TRegx\CleanRegex\Internal\PatternLimit;
use TRegx\CleanRegex\Internal\Subjectable;
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
    /** @var Subjectable */
    private $subjectable;
    /** @var string|int */
    private $nameOrIndex;

    public function __construct(callable $allFactory, callable $firstFactory, MatchOffsetLimitFactory $offsetLimitFactory, Subjectable $subjectable, $nameOrIndex)
    {
        $this->allFactory = $allFactory;
        $this->firstFactory = $firstFactory;
        $this->offsetLimitFactory = $offsetLimitFactory;
        $this->subjectable = $subjectable;
        $this->nameOrIndex = $nameOrIndex;
    }

    public function offsets(): OffsetLimit
    {
        return $this->offsetLimitFactory->create();
    }

    public function first(): string
    {
        return \call_user_func($this->firstFactory);
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
        return (new GroupFacade($rawMatches, $this->subjectable, $this->nameOrIndex, new MatchGroupFactoryStrategy(), new EagerMatchAllFactory($rawMatches)))->createGroups($rawMatches);
    }
}

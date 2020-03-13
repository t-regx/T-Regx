<?php
namespace TRegx\CleanRegex\Match;

use ArrayIterator;
use InvalidArgumentException;
use Iterator;
use TRegx\CleanRegex\Internal\Exception\Messages\NoFirstElementFluentMessage;
use TRegx\CleanRegex\Internal\Factory\NotMatchedFluentOptionalWorker;
use TRegx\CleanRegex\Internal\GroupLimit\GroupLimitAll;
use TRegx\CleanRegex\Internal\GroupLimit\GroupLimitFindFirst;
use TRegx\CleanRegex\Internal\GroupLimit\GroupLimitFirst;
use TRegx\CleanRegex\Internal\Match\Base\Base;
use TRegx\CleanRegex\Internal\Match\Details\Group\GroupFacade;
use TRegx\CleanRegex\Internal\Match\Details\Group\MatchGroupFactoryStrategy;
use TRegx\CleanRegex\Internal\Match\FlatMapper;
use TRegx\CleanRegex\Internal\Match\MatchAll\EagerMatchAllFactory;
use TRegx\CleanRegex\Internal\Match\MatchAll\LazyMatchAllFactory;
use TRegx\CleanRegex\Internal\Model\Match\RawMatchOffset;
use TRegx\CleanRegex\Internal\OffsetLimit\MatchOffsetLimitFactory;
use TRegx\CleanRegex\Internal\PatternLimit;
use TRegx\CleanRegex\Match\Details\Group\MatchGroup;
use TRegx\CleanRegex\Match\FindFirst\Optional;
use TRegx\CleanRegex\Match\Offset\OffsetLimit;

class GroupLimit implements PatternLimit
{
    /** @var GroupLimitAll */
    private $allFactory;
    /** @var GroupLimitFirst */
    private $firstFactory;
    /** @var GroupLimitFindFirst */
    private $findFirstFactory;

    /** @var Base */
    private $base;
    /** @var string|int */
    private $nameOrIndex;
    /** @var MatchOffsetLimitFactory */
    private $offsetLimitFactory;

    public function __construct(Base $base, $nameOrIndex, MatchOffsetLimitFactory $offsetLimitFactory)
    {
        $this->allFactory = new GroupLimitAll($base, $nameOrIndex);
        $this->firstFactory = new GroupLimitFirst($base, $nameOrIndex);
        $this->findFirstFactory = new GroupLimitFindFirst($base, $nameOrIndex);
        $this->offsetLimitFactory = $offsetLimitFactory;
        $this->base = $base;
        $this->nameOrIndex = $nameOrIndex;
    }

    /**
     * @param callable|null $consumer
     * @return string|mixed
     */
    public function first(callable $consumer = null)
    {
        $first = $this->firstFactory->getFirstForGroup();
        if ($consumer === null) {
            return $first->getGroup($this->nameOrIndex);
        }
        return $consumer($this->matchGroupDetails($first));
    }

    private function matchGroupDetails(RawMatchOffset $first): MatchGroup
    {
        $facade = new GroupFacade($first, $this->base, $this->nameOrIndex, new MatchGroupFactoryStrategy(), new LazyMatchAllFactory($this->base));
        return $facade->createGroup($first);
    }

    public function findFirst(callable $consumer): Optional
    {
        return $this->findFirstFactory->getOptionalForGroup($consumer);
    }

    /**
     * @return (string|null)[]
     */
    public function all(): array
    {
        return $this->allFactory->getAllForGroup()->getGroupTexts($this->nameOrIndex);
    }

    /**
     * @param int $limit
     * @return (string|null)[]
     */
    public function only(int $limit): array
    {
        $matches = $this->allFactory->getAllForGroup();
        if ($limit < 0) {
            throw new InvalidArgumentException("Negative limit: $limit");
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

    public function offsets(): OffsetLimit
    {
        return $this->offsetLimitFactory->create();
    }

    public function fluent(): FluentMatchPattern
    {
        return new FluentMatchPattern(
            $this->getMatchGroupObjects(),
            new NotMatchedFluentOptionalWorker(new NoFirstElementFluentMessage(), $this->base->getSubject()));
    }

    private function getMatchGroupObjects(): array
    {
        $matches = $this->allFactory->getAllForGroup();
        $groupFacade = new GroupFacade($matches, $this->base, $this->nameOrIndex,
            new MatchGroupFactoryStrategy(),
            new EagerMatchAllFactory($matches));
        return $groupFacade->createGroups($matches);
    }
}

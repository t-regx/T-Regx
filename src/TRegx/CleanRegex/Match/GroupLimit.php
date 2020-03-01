<?php
namespace TRegx\CleanRegex\Match;

use ArrayIterator;
use InvalidArgumentException;
use Iterator;
use TRegx\CleanRegex\Internal\Exception\Messages\NoFirstElementFluentMessage;
use TRegx\CleanRegex\Internal\Factory\NotMatchedFluentOptionalWorker;
use TRegx\CleanRegex\Internal\Match\Base\Base;
use TRegx\CleanRegex\Internal\Match\Details\Group\GroupFacade;
use TRegx\CleanRegex\Internal\Match\Details\Group\MatchGroupFactoryStrategy;
use TRegx\CleanRegex\Internal\Match\FlatMapper;
use TRegx\CleanRegex\Internal\Match\MatchAll\EagerMatchAllFactory;
use TRegx\CleanRegex\Internal\Match\MatchAll\LazyMatchAllFactory;
use TRegx\CleanRegex\Internal\Model\Match\RawMatchOffset;
use TRegx\CleanRegex\Internal\Model\Matches\IRawMatchesOffset;
use TRegx\CleanRegex\Internal\OffsetLimit\MatchOffsetLimitFactory;
use TRegx\CleanRegex\Internal\PatternLimit;
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
        /** @var RawMatchOffset $first */
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
        return $this->allMatches()->getGroupTexts($this->nameOrIndex);
    }

    /**
     * @param int $limit
     * @return (string|null)[]
     */
    public function only(int $limit): array
    {
        $matches = $this->allMatches();
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

    public function fluent(): FluentMatchPattern
    {
        return new FluentMatchPattern(
            $this->getMatchGroupObjects(),
            new NotMatchedFluentOptionalWorker(new NoFirstElementFluentMessage(), $this->base->getSubject()));
    }

    private function getMatchGroupObjects(): array
    {
        $matches = $this->allMatches();
        return (new GroupFacade($matches, $this->base, $this->nameOrIndex, new MatchGroupFactoryStrategy(), new EagerMatchAllFactory($matches)))->createGroups($matches);
    }

    private function allMatches(): IRawMatchesOffset
    {
        return \call_user_func($this->allFactory);
    }
}

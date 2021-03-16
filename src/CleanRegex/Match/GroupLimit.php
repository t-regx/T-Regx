<?php
namespace TRegx\CleanRegex\Match;

use ArrayIterator;
use InvalidArgumentException;
use Iterator;
use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\CleanRegex\Exception\NonexistentGroupException;
use TRegx\CleanRegex\Exception\NoSuchNthElementException;
use TRegx\CleanRegex\Exception\SubjectNotMatchedException;
use TRegx\CleanRegex\Internal\Exception\UnmatchedStreamException;
use TRegx\CleanRegex\Internal\Factory\Worker\MatchStreamWorker;
use TRegx\CleanRegex\Internal\Factory\Worker\ThrowInternalStreamWorker;
use TRegx\CleanRegex\Internal\GroupLimit\GroupLimitAll;
use TRegx\CleanRegex\Internal\GroupLimit\GroupLimitFindFirst;
use TRegx\CleanRegex\Internal\GroupLimit\GroupLimitFirst;
use TRegx\CleanRegex\Internal\Match\Base\Base;
use TRegx\CleanRegex\Internal\Match\Details\Group\GroupFacade;
use TRegx\CleanRegex\Internal\Match\Details\Group\MatchGroupFactoryStrategy;
use TRegx\CleanRegex\Internal\Match\FlatMap\ArrayMergeStrategy;
use TRegx\CleanRegex\Internal\Match\FlatMap\AssignStrategy;
use TRegx\CleanRegex\Internal\Match\FlatMapper;
use TRegx\CleanRegex\Internal\Match\MatchAll\LazyMatchAllFactory;
use TRegx\CleanRegex\Internal\Match\Stream\MatchGroupIntStream;
use TRegx\CleanRegex\Internal\Match\Stream\MatchGroupStream;
use TRegx\CleanRegex\Internal\Match\Stream\Stream;
use TRegx\CleanRegex\Internal\Model\Match\RawMatchOffset;
use TRegx\CleanRegex\Internal\PatternLimit;
use TRegx\CleanRegex\Match\Details\Group\Group;

class GroupLimit implements PatternLimit, \IteratorAggregate
{
    /** @var GroupLimitAll */
    private $allFactory;
    /** @var GroupLimitFirst */
    private $firstFactory;
    /** @var GroupLimitFindFirst */
    private $findFirstFactory;
    /** @var LazyMatchAllFactory */
    private $matchAllFactory;
    /** @var Base */
    private $base;
    /** @var string|int */
    private $nameOrIndex;
    /** @var OffsetLimit */
    private $offsetLimit;

    public function __construct(Base $base, $nameOrIndex, OffsetLimit $offsetLimit)
    {
        $this->allFactory = new GroupLimitAll($base, $nameOrIndex);
        $this->firstFactory = new GroupLimitFirst($base, $nameOrIndex);
        $this->findFirstFactory = new GroupLimitFindFirst($base, $nameOrIndex);
        $this->matchAllFactory = new LazyMatchAllFactory($base);
        $this->base = $base;
        $this->nameOrIndex = $nameOrIndex;
        $this->offsetLimit = $offsetLimit;
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

    private function matchGroupDetails(RawMatchOffset $first): Group
    {
        $facade = new GroupFacade($first, $this->base, $this->nameOrIndex, new MatchGroupFactoryStrategy(), $this->matchAllFactory);
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
        return \array_values($this->allFactory->getAllForGroup()->getGroupTexts($this->nameOrIndex));
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

    public function nth(int $index): string
    {
        $match = $this->base->matchAllOffsets();
        $count = $match->getCount();
        if (!$match->hasGroup($this->nameOrIndex)) {
            throw new NonexistentGroupException($this->nameOrIndex);
        }
        if ($index < 0) {
            throw new InvalidArgumentException("Negative group nth: $index");
        }
        if ($count === 0) {
            throw SubjectNotMatchedException::forNthGroup($this->base, $this->nameOrIndex, $index);
        }
        if ($count <= $index) {
            throw NoSuchNthElementException::forGroup($this->nameOrIndex, $index, $count);
        }
        if (!$match->isGroupMatched($this->nameOrIndex, $index)) {
            throw GroupNotMatchedException::forNth($this->base, $this->nameOrIndex, $index);
        }
        [$text, $offset] = $match->getGroupTextAndOffset($this->nameOrIndex, $index);
        return $text;
    }

    public function getIterator(): Iterator
    {
        return new ArrayIterator(\array_values($this->details()));
    }

    public function map(callable $mapper): array
    {
        return \array_map($mapper, $this->details());
    }

    public function flatMap(callable $mapper): array
    {
        return (new FlatMapper(new ArrayMergeStrategy(), $mapper, 'flatMap'))->get($this->details());
    }

    public function flatMapAssoc(callable $mapper): array
    {
        return (new FlatMapper(new AssignStrategy(), $mapper, 'flatMapAssoc'))->get($this->details());
    }

    /**
     * @param callable $consumer
     * @return string[]
     */
    public function filter(callable $consumer): array
    {
        /**
         * I use foreach, instead of \array_map() to eliminate the overhead of PHP function call.
         * I use \array_filter(), because we have to call user function no matter what,
         */
        $result = [];
        foreach (\array_filter($this->details(), $consumer) as $group) {
            $result[] = $group->text();
        }
        return $result;
    }

    public function forEach(callable $consumer): void
    {
        foreach ($this->details() as $group) {
            $consumer($group);
        }
    }

    public function offsets(): OffsetLimit
    {
        return $this->offsetLimit;
    }

    public function fluent(): FluentMatchPattern
    {
        return new FluentMatchPattern($this->stream(), new MatchStreamWorker());
    }

    public function asInt(): FluentMatchPattern
    {
        return new FluentMatchPattern(
            new MatchGroupIntStream($this->base, $this->nameOrIndex, $this->matchAllFactory),
            new ThrowInternalStreamWorker());
    }

    private function stream(): Stream
    {
        return new MatchGroupStream($this->base, $this->nameOrIndex, $this->matchAllFactory);
    }

    private function details(): array
    {
        try {
            return $this->stream()->all();
        } catch (UnmatchedStreamException $exception) {
            return [];
        }
    }
}

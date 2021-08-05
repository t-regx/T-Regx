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
use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\GroupKey\PerformanceSignatures;
use TRegx\CleanRegex\Internal\GroupLimit\GroupLimitFindFirst;
use TRegx\CleanRegex\Internal\GroupLimit\GroupLimitFirst;
use TRegx\CleanRegex\Internal\Match\Base\Base;
use TRegx\CleanRegex\Internal\Match\Details\Group\GroupFacade;
use TRegx\CleanRegex\Internal\Match\Details\Group\Handle\FirstNamedGroup;
use TRegx\CleanRegex\Internal\Match\Details\Group\MatchGroupFactoryStrategy;
use TRegx\CleanRegex\Internal\Match\FlatFunction;
use TRegx\CleanRegex\Internal\Match\FlatMap\ArrayMergeStrategy;
use TRegx\CleanRegex\Internal\Match\FlatMap\AssignStrategy;
use TRegx\CleanRegex\Internal\Match\MatchAll\LazyMatchAllFactory;
use TRegx\CleanRegex\Internal\Match\Stream\MatchGroupIntStream;
use TRegx\CleanRegex\Internal\Match\Stream\MatchGroupStream;
use TRegx\CleanRegex\Internal\Match\Stream\Stream;
use TRegx\CleanRegex\Internal\Model\FalseNegative;
use TRegx\CleanRegex\Internal\Model\GroupAware;
use TRegx\CleanRegex\Internal\Model\Match\RawMatchesOffset;
use TRegx\CleanRegex\Internal\Nested;
use TRegx\CleanRegex\Internal\PatternLimit;
use TRegx\SafeRegex\Internal\Tuple;

class GroupLimit implements PatternLimit, \IteratorAggregate
{
    /** @var Base */
    private $base;
    /** @var GroupAware */
    private $groupAware;
    /** @var GroupLimitFirst */
    private $firstFactory;
    /** @var GroupLimitFindFirst */
    private $findFirstFactory;
    /** @var LazyMatchAllFactory */
    private $matchAllFactory;
    /** @var GroupKey */
    private $groupId;

    public function __construct(Base $base, GroupAware $groupAware, GroupKey $groupId)
    {
        $this->base = $base;
        $this->groupAware = $groupAware;
        $this->firstFactory = new GroupLimitFirst($base, $groupAware, $groupId);
        $this->findFirstFactory = new GroupLimitFindFirst($base, $groupAware, $groupId);
        $this->matchAllFactory = new LazyMatchAllFactory($base);
        $this->groupId = $groupId;
    }

    /**
     * @param callable|null $consumer
     * @return string|mixed
     */
    public function first(callable $consumer = null)
    {
        $first = $this->firstFactory->getFirstForGroup();
        if ($consumer === null) {
            return $first->getGroup($this->groupId->nameOrIndex());
        }
        $signatures = new PerformanceSignatures($first, $this->groupAware);
        $false = new FalseNegative($first);
        $facade = new GroupFacade($false, $this->base, $this->groupId,
            new MatchGroupFactoryStrategy(),
            $this->matchAllFactory,
            new FirstNamedGroup($signatures),
            $signatures);
        return $consumer($facade->createGroup($false));
    }

    public function findFirst(callable $consumer): Optional
    {
        return $this->findFirstFactory->getOptionalForGroup($consumer);
    }

    public function all(): array
    {
        return \array_values($this->getAllForGroup()->getGroupTexts($this->groupId->nameOrIndex()));
    }

    public function only(int $limit): array
    {
        $matches = $this->getAllForGroup();
        if ($limit < 0) {
            throw new InvalidArgumentException("Negative limit: $limit");
        }
        return \array_slice($matches->getGroupTexts($this->groupId->nameOrIndex()), 0, $limit);
    }

    private function getAllForGroup(): RawMatchesOffset
    {
        $rawMatches = $this->base->matchAllOffsets();
        if ($rawMatches->hasGroup($this->groupId->nameOrIndex())) {
            return $rawMatches;
        }
        throw new NonexistentGroupException($this->groupId);
    }

    public function nth(int $index): string
    {
        $match = $this->base->matchAllOffsets();
        $count = $match->getCount();
        if (!$match->hasGroup($this->groupId->nameOrIndex())) {
            throw new NonexistentGroupException($this->groupId);
        }
        if ($index < 0) {
            throw new InvalidArgumentException("Negative group nth: $index");
        }
        if (!$match->matched()) {
            throw SubjectNotMatchedException::forNthGroup($this->base, $this->groupId, $index);
        }
        if ($count <= $index) {
            throw NoSuchNthElementException::forGroup($this->groupId, $index, $count);
        }
        if (!$match->isGroupMatched($this->groupId->nameOrIndex(), $index)) {
            throw GroupNotMatchedException::forNth($this->base, $this->groupId, $index);
        }
        return Tuple::first($match->getGroupTextAndOffset($this->groupId->nameOrIndex(), $index));
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
        $function = new FlatFunction($mapper, 'flatMap');
        return (new ArrayMergeStrategy())->flatten(new Nested(\array_map([$function, 'apply'], $this->details())));
    }

    public function flatMapAssoc(callable $mapper): array
    {
        $function = new FlatFunction($mapper, 'flatMapAssoc');
        return (new AssignStrategy())->flatten(new Nested(\array_map([$function, 'apply'], $this->details())));
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
        return new OffsetLimit($this->base, $this->groupAware, $this->groupId, false);
    }

    public function fluent(): FluentMatchPattern
    {
        return new FluentMatchPattern($this->stream(), new MatchStreamWorker());
    }

    public function asInt(): FluentMatchPattern
    {
        return new FluentMatchPattern(
            new MatchGroupIntStream($this->base, $this->groupId, $this->matchAllFactory),
            new ThrowInternalStreamWorker());
    }

    private function stream(): Stream
    {
        return new MatchGroupStream($this->base, $this->groupAware, $this->groupId, $this->matchAllFactory);
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

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
use TRegx\CleanRegex\Internal\Match\Details\Group\GroupFacadeMatched;
use TRegx\CleanRegex\Internal\Match\Details\Group\Handle\FirstNamedGroup;
use TRegx\CleanRegex\Internal\Match\Details\Group\MatchGroupFactoryStrategy;
use TRegx\CleanRegex\Internal\Match\FlatFunction;
use TRegx\CleanRegex\Internal\Match\FlatMap\ArrayMergeStrategy;
use TRegx\CleanRegex\Internal\Match\FlatMap\AssignStrategy;
use TRegx\CleanRegex\Internal\Match\MatchAll\LazyMatchAllFactory;
use TRegx\CleanRegex\Internal\Match\Stream\Base\MatchGroupIntStream;
use TRegx\CleanRegex\Internal\Match\Stream\Base\MatchGroupOffsetStream;
use TRegx\CleanRegex\Internal\Match\Stream\Base\MatchGroupStream;
use TRegx\CleanRegex\Internal\Match\Stream\Stream;
use TRegx\CleanRegex\Internal\Model\FalseNegative;
use TRegx\CleanRegex\Internal\Model\GroupAware;
use TRegx\CleanRegex\Internal\Model\Match\RawMatchesOffset;
use TRegx\CleanRegex\Internal\Nested;
use TRegx\CleanRegex\Internal\Number;
use TRegx\CleanRegex\Internal\Predicate;
use TRegx\SafeRegex\Internal\Tuple;

class GroupLimit implements \IteratorAggregate
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
    private $group;

    public function __construct(Base $base, GroupAware $groupAware, GroupKey $group)
    {
        $this->base = $base;
        $this->groupAware = $groupAware;
        $this->firstFactory = new GroupLimitFirst($base, $groupAware, $group);
        $this->findFirstFactory = new GroupLimitFindFirst($base, $groupAware, $group);
        $this->matchAllFactory = new LazyMatchAllFactory($base);
        $this->group = $group;
    }

    /**
     * @param callable|null $consumer
     * @return string|mixed
     */
    public function first(callable $consumer = null)
    {
        $first = $this->firstFactory->getFirstForGroup();
        if ($consumer === null) {
            return $first->getGroup($this->group->nameOrIndex());
        }
        $signatures = new PerformanceSignatures($first, $this->groupAware);
        $facade = new GroupFacadeMatched($this->base,
            new MatchGroupFactoryStrategy(),
            $this->matchAllFactory,
            new FirstNamedGroup($signatures),
            $signatures);
        $false = new FalseNegative($first);
        return $consumer($facade->createGroup($this->group, $false, $false));
    }

    public function findFirst(callable $consumer): Optional
    {
        return $this->findFirstFactory->getOptionalForGroup($consumer);
    }

    public function all(): array
    {
        return \array_values($this->getAllForGroup()->getGroupTexts($this->group->nameOrIndex()));
    }

    public function only(int $limit): array
    {
        $matches = $this->getAllForGroup();
        if ($limit < 0) {
            throw new InvalidArgumentException("Negative limit: $limit");
        }
        return \array_slice($matches->getGroupTexts($this->group->nameOrIndex()), 0, $limit);
    }

    private function getAllForGroup(): RawMatchesOffset
    {
        $rawMatches = $this->base->matchAllOffsets();
        if ($rawMatches->hasGroup($this->group->nameOrIndex())) {
            return $rawMatches;
        }
        throw new NonexistentGroupException($this->group);
    }

    public function nth(int $index): string
    {
        $match = $this->base->matchAllOffsets();
        $count = $match->getCount();
        if (!$match->hasGroup($this->group->nameOrIndex())) {
            throw new NonexistentGroupException($this->group);
        }
        if ($index < 0) {
            throw new InvalidArgumentException("Negative group nth: $index");
        }
        if (!$match->matched()) {
            throw SubjectNotMatchedException::forNthGroup($this->base, $this->group, $index);
        }
        if ($count <= $index) {
            throw NoSuchNthElementException::forGroup($this->group, $index, $count);
        }
        if (!$match->isGroupMatched($this->group->nameOrIndex(), $index)) {
            throw GroupNotMatchedException::forNth($this->group, $index);
        }
        return Tuple::first($match->getGroupTextAndOffset($this->group->nameOrIndex(), $index));
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
     * @param callable $predicate
     * @return string[]
     */
    public function filter(callable $predicate): array
    {
        return $this->filtered(new Predicate($predicate, 'filter'));
    }

    private function filtered(Predicate $predicate): array
    {
        /**
         * I use foreach, instead of \array_map() to eliminate the overhead of PHP function call.
         * I use \array_filter(), because we have to call user function no matter what,
         */
        $result = [];
        foreach (\array_filter($this->details(), [$predicate, 'test']) as $group) {
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

    public function offsets(): FluentMatchPattern
    {
        return new FluentMatchPattern(
            new MatchGroupOffsetStream($this->base, $this->group, $this->matchAllFactory),
            new ThrowInternalStreamWorker());
    }

    public function fluent(): FluentMatchPattern
    {
        return new FluentMatchPattern($this->stream(), new MatchStreamWorker());
    }

    public function asInt(int $base = null): FluentMatchPattern
    {
        return new FluentMatchPattern(
            new MatchGroupIntStream($this->base, $this->group, $this->matchAllFactory, new Number\Base($base)),
            new ThrowInternalStreamWorker());
    }

    private function stream(): Stream
    {
        return new MatchGroupStream($this->base, $this->groupAware, $this->group, $this->matchAllFactory);
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

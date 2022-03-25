<?php
namespace TRegx\CleanRegex\Match;

use ArrayIterator;
use InvalidArgumentException;
use Iterator;
use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\CleanRegex\Exception\NonexistentGroupException;
use TRegx\CleanRegex\Exception\NoSuchNthElementException;
use TRegx\CleanRegex\Exception\SubjectNotMatchedException;
use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\GroupKey\PerformanceSignatures;
use TRegx\CleanRegex\Internal\GroupLimit\GroupMatchFindFirst;
use TRegx\CleanRegex\Internal\Match\Base\Base;
use TRegx\CleanRegex\Internal\Match\Details\Group\GroupFacadeMatched;
use TRegx\CleanRegex\Internal\Match\Details\Group\Handle\FirstNamedGroup;
use TRegx\CleanRegex\Internal\Match\Details\Group\MatchGroupFactoryStrategy;
use TRegx\CleanRegex\Internal\Match\FlatFunction;
use TRegx\CleanRegex\Internal\Match\FlatMap\ArrayMergeStrategy;
use TRegx\CleanRegex\Internal\Match\FlatMap\AssignStrategy;
use TRegx\CleanRegex\Internal\Match\IntStream\GroupIntMessages;
use TRegx\CleanRegex\Internal\Match\IntStream\GroupOffsetMessages;
use TRegx\CleanRegex\Internal\Match\IntStream\NthIntStreamElement;
use TRegx\CleanRegex\Internal\Match\MatchAll\LazyMatchAllFactory;
use TRegx\CleanRegex\Internal\Match\Stream\Base\MatchGroupIntStream;
use TRegx\CleanRegex\Internal\Match\Stream\Base\MatchGroupOffsetStream;
use TRegx\CleanRegex\Internal\Match\Stream\Base\MatchGroupStream;
use TRegx\CleanRegex\Internal\Match\Stream\Base\UnmatchedStreamException;
use TRegx\CleanRegex\Internal\Match\Stream\Upstream;
use TRegx\CleanRegex\Internal\Model\FalseNegative;
use TRegx\CleanRegex\Internal\Model\GroupAware;
use TRegx\CleanRegex\Internal\Model\Match\RawMatchesOffset;
use TRegx\CleanRegex\Internal\Model\Match\RawMatchOffset;
use TRegx\CleanRegex\Internal\Numeral;
use TRegx\CleanRegex\Internal\Predicate;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\SafeRegex\Internal\Tuple;

class GroupMatch implements \IteratorAggregate
{
    /** @var Base */
    private $base;
    /** @var Subject */
    private $subject;
    /** @var GroupAware */
    private $groupAware;
    /** @var GroupMatchFindFirst */
    private $findFirstFactory;
    /** @var LazyMatchAllFactory */
    private $matchAllFactory;
    /** @var GroupKey */
    private $group;

    public function __construct(Base $base, Subject $subject, GroupAware $groupAware, GroupKey $group)
    {
        $this->base = $base;
        $this->subject = $subject;
        $this->groupAware = $groupAware;
        $this->findFirstFactory = new GroupMatchFindFirst($base, $subject, $groupAware, $group);
        $this->matchAllFactory = new LazyMatchAllFactory($base);
        $this->group = $group;
    }

    /**
     * @param callable|null $consumer
     * @return string|mixed
     */
    public function first(callable $consumer = null)
    {
        $first = $this->getFirstForGroup();
        if ($consumer === null) {
            return $first->getGroup($this->group->nameOrIndex());
        }
        $signatures = new PerformanceSignatures($first, $this->groupAware);
        $facade = new GroupFacadeMatched($this->subject,
            new MatchGroupFactoryStrategy(),
            $this->matchAllFactory,
            new FirstNamedGroup($signatures),
            $signatures);
        $false = new FalseNegative($first);
        return $consumer($facade->createGroup($this->group, $false, $false));
    }

    private function getFirstForGroup(): RawMatchOffset
    {
        $rawMatch = $this->base->matchOffset();
        if ($rawMatch->hasGroup($this->group)) {
            $group = $rawMatch->getGroup($this->group->nameOrIndex());
            if ($group !== null) {
                return $rawMatch;
            }
        } else {
            if (!$this->groupAware->hasGroup($this->group)) {
                throw new NonexistentGroupException($this->group);
            }
            if (!$rawMatch->matched()) {
                throw SubjectNotMatchedException::forFirstGroup($this->subject, $this->group);
            }
        }
        throw GroupNotMatchedException::forFirst($this->group);
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
        if ($rawMatches->hasGroup($this->group)) {
            return $rawMatches;
        }
        throw new NonexistentGroupException($this->group);
    }

    public function nth(int $index): string
    {
        $match = $this->base->matchAllOffsets();
        if (!$match->hasGroup($this->group)) {
            throw new NonexistentGroupException($this->group);
        }
        if ($index < 0) {
            throw new InvalidArgumentException("Negative group nth: $index");
        }
        $count = $match->getCount();
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
        return (new ArrayMergeStrategy())->flatten($function->map($this->details()));
    }

    public function flatMapAssoc(callable $mapper): array
    {
        $function = new FlatFunction($mapper, 'flatMapAssoc');
        return (new AssignStrategy())->flatten($function->map($this->details()));
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
        foreach ($this->details() as $index => $group) {
            $consumer($group, $index);
        }
    }

    public function offsets(): IntStream
    {
        $upstream = new MatchGroupOffsetStream($this->base, $this->subject, $this->group, $this->matchAllFactory);
        return new IntStream($upstream, new NthIntStreamElement($upstream, $this->subject, new GroupOffsetMessages($this->group)), $this->subject);
    }

    public function stream(): Stream
    {
        return new Stream($this->upstream(), $this->subject);
    }

    public function asInt(int $base = null): IntStream
    {
        $upstream = new MatchGroupIntStream($this->base, $this->subject, $this->group, $this->matchAllFactory, new Numeral\Base($base));
        return new IntStream($upstream, new NthIntStreamElement($upstream, $this->subject, new GroupIntMessages($this->group)), $this->subject);
    }

    private function upstream(): Upstream
    {
        return new MatchGroupStream($this->base, $this->subject, $this->groupAware, $this->group, $this->matchAllFactory);
    }

    private function details(): array
    {
        try {
            return $this->upstream()->all();
        } catch (UnmatchedStreamException $exception) {
            return [];
        }
    }
}

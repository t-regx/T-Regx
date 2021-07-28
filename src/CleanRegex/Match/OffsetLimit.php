<?php
namespace TRegx\CleanRegex\Match;

use InvalidArgumentException;
use Iterator;
use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\CleanRegex\Exception\NonexistentGroupException;
use TRegx\CleanRegex\Exception\SubjectNotMatchedException;
use TRegx\CleanRegex\Internal\Factory\Worker\FluentStreamWorker;
use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\Match\Base\Base;
use TRegx\CleanRegex\Internal\Match\OffsetLimitStream;
use TRegx\CleanRegex\Internal\Model\GroupHasAware;
use TRegx\CleanRegex\Internal\PatternLimit;

class OffsetLimit implements PatternLimit, \IteratorAggregate
{
    /** @var Base */
    private $base;
    /** @var GroupKey */
    private $groupId;
    /** @var bool */
    private $isWholeMatch;
    /** @var GroupHasAware */
    private $groupAware;

    public function __construct(Base $base, GroupHasAware $groupAware, GroupKey $groupId, bool $isWholeMatch)
    {
        $this->base = $base;
        $this->groupId = $groupId;
        $this->isWholeMatch = $isWholeMatch;
        $this->groupAware = $groupAware;
    }

    public function first(): int
    {
        $rawMatch = $this->base->matchOffset();
        if ($rawMatch->hasGroup($this->groupId->nameOrIndex())) {
            $group = $rawMatch->getGroupByteOffset($this->groupId->nameOrIndex());
            if ($group !== null) {
                return $group;
            }
            throw GroupNotMatchedException::forFirst($this->base, $this->groupId);
        }
        if (!$this->groupAware->hasGroup($this->groupId->nameOrIndex())) {
            throw new NonexistentGroupException($this->groupId);
        }
        if ($rawMatch->matched()) {
            throw GroupNotMatchedException::forFirst($this->base, $this->groupId);
        }
        if ($this->isWholeMatch) {
            throw SubjectNotMatchedException::forFirstOffset($this->base);
        }
        throw SubjectNotMatchedException::forFirstGroupOffset($this->base, $this->groupId);
    }

    public function all(): array
    {
        $matches = $this->base->matchAllOffsets();
        if (!$matches->hasGroup($this->groupId->nameOrIndex())) {
            throw new NonexistentGroupException($this->groupId);
        }
        return \array_values($matches->getLimitedGroupOffsets($this->groupId->nameOrIndex(), -1));
    }

    public function getIterator(): Iterator
    {
        return new \ArrayIterator($this->all());
    }

    public function only(int $limit): array
    {
        $matches = $this->base->matchAllOffsets();
        if (!$matches->hasGroup($this->groupId->nameOrIndex())) {
            throw new NonexistentGroupException($this->groupId);
        }
        if ($limit < 0) {
            throw new InvalidArgumentException("Negative limit: $limit");
        }
        return $matches->getLimitedGroupOffsets($this->groupId->nameOrIndex(), $limit);
    }

    public function fluent(): FluentMatchPattern
    {
        return new FluentMatchPattern(
            new OffsetLimitStream($this->base, $this->groupId, $this->groupAware),
            new FluentStreamWorker());
    }
}

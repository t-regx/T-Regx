<?php
namespace TRegx\CleanRegex\Internal\Match;

use TRegx\CleanRegex\Exception\NonexistentGroupException;
use TRegx\CleanRegex\Internal\Exception\NoFirstStreamException;
use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\Match\Base\Base;
use TRegx\CleanRegex\Internal\Match\Stream\Stream;
use TRegx\CleanRegex\Internal\Model\GroupHasAware;

class OffsetLimitStream implements Stream
{
    /** @var Base */
    private $base;
    /** @var GroupKey */
    private $groupId;
    /** @var GroupHasAware */
    private $groupAware;

    public function __construct(Base $base, GroupKey $groupId, GroupHasAware $groupAware)
    {
        $this->base = $base;
        $this->groupId = $groupId;
        $this->groupAware = $groupAware;
    }

    public function all(): array
    {
        $matches = $this->base->matchAllOffsets();
        if (!$matches->hasGroup($this->groupId->nameOrIndex())) {
            throw new NonexistentGroupException($this->groupId);
        }
        return $matches->getLimitedGroupOffsets($this->groupId->nameOrIndex(), -1);
    }

    public function first(): int
    {
        [$first, $firstKey] = $this->getFirstAndKey();
        return $first;
    }

    public function firstKey(): int
    {
        [$first, $firstKey] = $this->getFirstAndKey();
        return $firstKey;
    }

    private function getFirstAndKey(): array
    {
        $rawMatch = $this->base->matchOffset();
        if ($rawMatch->hasGroup($this->groupId->nameOrIndex())) {
            $group = $rawMatch->getGroupByteOffset($this->groupId->nameOrIndex());
            if ($group !== null) {
                return [$group, $rawMatch->getIndex()];
            }
            throw new NoFirstStreamException();
        }
        if ($this->groupAware->hasGroup($this->groupId->nameOrIndex())) {
            throw new NoFirstStreamException();
        }
        throw new NonexistentGroupException($this->groupId);
    }
}

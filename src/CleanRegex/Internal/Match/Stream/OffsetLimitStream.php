<?php
namespace TRegx\CleanRegex\Internal\Match\Stream;

use TRegx\CleanRegex\Exception\NonexistentGroupException;
use TRegx\CleanRegex\Internal\Exception\NoFirstStreamException;
use TRegx\CleanRegex\Internal\Exception\UnmatchedStreamException;
use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\Match\Base\Base;
use TRegx\CleanRegex\Internal\Model\GroupHasAware;

class OffsetLimitStream implements Stream
{
    use ListStream;

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

    protected function entries(): array
    {
        $matches = $this->base->matchAllOffsets();
        if (!$matches->hasGroup($this->groupId->nameOrIndex())) {
            throw new NonexistentGroupException($this->groupId);
        }
        if ($matches->matched()) {
            return $matches->getLimitedGroupOffsets($this->groupId->nameOrIndex(), -1);
        }
        throw new UnmatchedStreamException();
    }

    protected function firstValue(): int
    {
        $match = $this->base->matchOffset();
        if (!$match->hasGroup($this->groupId->nameOrIndex())) {
            if (!$this->groupAware->hasGroup($this->groupId->nameOrIndex())) {
                throw new NonexistentGroupException($this->groupId);
            }
        }
        if (!$match->matched()) {
            throw new UnmatchedStreamException();
        }
        $group = $match->getGroupByteOffset($this->groupId->nameOrIndex());
        if ($group !== null) {
            return $group;
        }
        throw new NoFirstStreamException();
    }
}

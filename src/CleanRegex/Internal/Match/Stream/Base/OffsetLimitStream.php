<?php
namespace TRegx\CleanRegex\Internal\Match\Stream\Base;

use TRegx\CleanRegex\Exception\NonexistentGroupException;
use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\Match\Base\Base;
use TRegx\CleanRegex\Internal\Match\Stream\ListStream;
use TRegx\CleanRegex\Internal\Match\Stream\NoFirstStreamException;
use TRegx\CleanRegex\Internal\Match\Stream\Stream;
use TRegx\CleanRegex\Internal\Model\GroupHasAware;

class OffsetLimitStream implements Stream
{
    use ListStream;

    /** @var Base */
    private $base;
    /** @var GroupKey */
    private $group;
    /** @var GroupHasAware */
    private $groupAware;

    public function __construct(Base $base, GroupKey $group, GroupHasAware $groupAware)
    {
        $this->base = $base;
        $this->group = $group;
        $this->groupAware = $groupAware;
    }

    protected function entries(): array
    {
        $matches = $this->base->matchAllOffsets();
        if (!$matches->hasGroup($this->group->nameOrIndex())) {
            throw new NonexistentGroupException($this->group);
        }
        if ($matches->matched()) {
            return $matches->getLimitedGroupOffsets($this->group->nameOrIndex(), -1);
        }
        throw new UnmatchedStreamException();
    }

    protected function firstValue(): int
    {
        $match = $this->base->matchOffset();
        if (!$match->hasGroup($this->group->nameOrIndex())) {
            if (!$this->groupAware->hasGroup($this->group->nameOrIndex())) {
                throw new NonexistentGroupException($this->group);
            }
        }
        if (!$match->matched()) {
            throw new UnmatchedStreamException();
        }
        $group = $match->getGroupByteOffset($this->group->nameOrIndex());
        if ($group !== null) {
            return $group;
        }
        throw new NoFirstStreamException();
    }
}

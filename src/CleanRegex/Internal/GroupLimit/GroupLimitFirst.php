<?php
namespace TRegx\CleanRegex\Internal\GroupLimit;

use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\CleanRegex\Exception\NonexistentGroupException;
use TRegx\CleanRegex\Exception\SubjectNotMatchedException;
use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\Match\Base\Base;
use TRegx\CleanRegex\Internal\Model\GroupHasAware;
use TRegx\CleanRegex\Internal\Model\Match\RawMatchOffset;

class GroupLimitFirst
{
    /** @var Base */
    private $base;
    /** @var GroupHasAware */
    private $groupAware;
    /** @var GroupKey */
    private $groupId;

    public function __construct(Base $base, GroupHasAware $groupAware, GroupKey $groupId)
    {
        $this->base = $base;
        $this->groupAware = $groupAware;
        $this->groupId = $groupId;
    }

    public function getFirstForGroup(): RawMatchOffset
    {
        $rawMatch = $this->base->matchOffset();
        if ($rawMatch->hasGroup($this->groupId->nameOrIndex())) {
            $group = $rawMatch->getGroup($this->groupId->nameOrIndex());
            if ($group !== null) {
                return $rawMatch;
            }
        } else {
            if (!$this->groupAware->hasGroup($this->groupId->nameOrIndex())) {
                throw new NonexistentGroupException($this->groupId);
            }
            if (!$rawMatch->matched()) {
                throw SubjectNotMatchedException::forFirstGroup($this->base, $this->groupId);
            }
        }
        throw GroupNotMatchedException::forFirst($this->base, $this->groupId);
    }
}

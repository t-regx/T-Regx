<?php
namespace TRegx\CleanRegex\Internal\GroupLimit;

use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\CleanRegex\Exception\NonexistentGroupException;
use TRegx\CleanRegex\Exception\SubjectNotMatchedException;
use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\Match\Base\Base;
use TRegx\CleanRegex\Internal\Model\GroupHasAware;
use TRegx\CleanRegex\Internal\Model\Match\RawMatchOffset;
use TRegx\CleanRegex\Internal\Subject;

class GroupLimitFirst
{
    /** @var Base */
    private $base;
    /** @var GroupHasAware */
    private $groupAware;
    /** @var GroupKey */
    private $group;
    /** @var Subject */
    private $subject;

    public function __construct(Base $base, Subject $subject, GroupHasAware $groupAware, GroupKey $group)
    {
        $this->base = $base;
        $this->groupAware = $groupAware;
        $this->group = $group;
        $this->subject = $subject;
    }

    public function getFirstForGroup(): RawMatchOffset
    {
        $rawMatch = $this->base->matchOffset();
        if ($rawMatch->hasGroup($this->group->nameOrIndex())) {
            $group = $rawMatch->getGroup($this->group->nameOrIndex());
            if ($group !== null) {
                return $rawMatch;
            }
        } else {
            if (!$this->groupAware->hasGroup($this->group->nameOrIndex())) {
                throw new NonexistentGroupException($this->group);
            }
            if (!$rawMatch->matched()) {
                throw SubjectNotMatchedException::forFirstGroup($this->subject, $this->group);
            }
        }
        throw GroupNotMatchedException::forFirst($this->group);
    }
}

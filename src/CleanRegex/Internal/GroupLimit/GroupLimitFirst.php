<?php
namespace TRegx\CleanRegex\Internal\GroupLimit;

use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\CleanRegex\Exception\NonexistentGroupException;
use TRegx\CleanRegex\Exception\SubjectNotMatchedException;
use TRegx\CleanRegex\Internal\Match\Base\Base;
use TRegx\CleanRegex\Internal\Model\GroupHasAware;
use TRegx\CleanRegex\Internal\Model\Match\RawMatchOffset;

class GroupLimitFirst
{
    /** @var Base */
    private $base;
    /** @var GroupHasAware */
    private $groupAware;
    /** @var string|int */
    private $nameOrIndex;

    public function __construct(Base $base, GroupHasAware $groupAware, $nameOrIndex)
    {
        $this->base = $base;
        $this->groupAware = $groupAware;
        $this->nameOrIndex = $nameOrIndex;
    }

    public function getFirstForGroup(): RawMatchOffset
    {
        $rawMatch = $this->base->matchOffset();
        if ($rawMatch->hasGroup($this->nameOrIndex)) {
            $group = $rawMatch->getGroup($this->nameOrIndex);
            if ($group !== null) {
                return $rawMatch;
            }
        } else {
            if (!$this->groupAware->hasGroup($this->nameOrIndex)) {
                throw new NonexistentGroupException($this->nameOrIndex);
            }
            if (!$rawMatch->matched()) {
                throw SubjectNotMatchedException::forFirstGroup($this->base, $this->nameOrIndex);
            }
        }
        throw GroupNotMatchedException::forFirst($this->base, $this->nameOrIndex);
    }
}

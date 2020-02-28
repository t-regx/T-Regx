<?php
namespace TRegx\CleanRegex\Internal\GroupLimit;

use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\CleanRegex\Exception\NonexistentGroupException;
use TRegx\CleanRegex\Exception\SubjectNotMatchedException;
use TRegx\CleanRegex\Internal\Match\Base\Base;
use TRegx\CleanRegex\Internal\Model\Match\RawMatchOffset;
use TRegx\CleanRegex\Match\Groups\Strategy\GroupVerifier;
use TRegx\CleanRegex\Match\Groups\Strategy\MatchAllGroupVerifier;

class GroupLimitFirst
{
    /** @var Base */
    private $base;
    /** @var string|int */
    private $nameOrIndex;
    /** @var GroupVerifier */
    private $groupVerifier;

    public function __construct(Base $base, $nameOrIndex)
    {
        $this->base = $base;
        $this->nameOrIndex = $nameOrIndex;
        $this->groupVerifier = new MatchAllGroupVerifier($this->base->getPattern());
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
            if (!$this->groupExists()) {
                throw new NonexistentGroupException($this->nameOrIndex);
            }
            if (!$rawMatch->matched()) {
                throw SubjectNotMatchedException::forFirstGroup($this->base, $this->nameOrIndex);
            }
        }
        throw GroupNotMatchedException::forFirst($this->base, $this->nameOrIndex);
    }

    private function groupExists(): bool
    {
        return $this->groupVerifier->groupExists($this->nameOrIndex);
    }
}

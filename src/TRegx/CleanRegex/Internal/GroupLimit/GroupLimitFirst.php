<?php
namespace TRegx\CleanRegex\Internal\GroupLimit;

use TRegx\CleanRegex\Exception\CleanRegex\GroupNotMatchedException;
use TRegx\CleanRegex\Exception\CleanRegex\NonexistentGroupException;
use TRegx\CleanRegex\Exception\CleanRegex\SubjectNotMatchedException;
use TRegx\CleanRegex\Internal\Match\Base\Base;
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

    public function getFirstForGroup(): string
    {
        $rawMatch = $this->base->matchGroupable();
        if ($rawMatch->hasGroup($this->nameOrIndex)) {
            $group = $rawMatch->getGroup($this->nameOrIndex);
            if ($group !== null) {
                return $group;
            }
        } else {
            if (!$this->groupVerifier->groupExists($this->nameOrIndex)) {
                throw new NonexistentGroupException($this->nameOrIndex);
            }
            if (!$rawMatch->matched()) {
                throw SubjectNotMatchedException::forFirst($this->base);
            }
        }
        throw GroupNotMatchedException::forFirst($this->base, $this->nameOrIndex);
    }
}

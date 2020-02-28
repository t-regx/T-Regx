<?php
namespace TRegx\CleanRegex\Internal\OffsetLimit;

use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\CleanRegex\Exception\NonexistentGroupException;
use TRegx\CleanRegex\Exception\SubjectNotMatchedException;
use TRegx\CleanRegex\Internal\Match\Base\Base;
use TRegx\CleanRegex\Match\Groups\Strategy\GroupVerifier;
use TRegx\CleanRegex\Match\Groups\Strategy\MatchAllGroupVerifier;

class MatchOffsetLimitFirst
{
    /** @var Base */
    private $base;
    /** @var string|int */
    private $nameOrIndex;
    /** @var GroupVerifier */
    private $groupVerifier;
    /** @var bool */
    private $isWholeMatch;

    public function __construct(Base $base, $nameOrIndex, bool $isWholeMatch)
    {
        $this->base = $base;
        $this->nameOrIndex = $nameOrIndex;
        $this->groupVerifier = new MatchAllGroupVerifier($this->base->getPattern());
        $this->isWholeMatch = $isWholeMatch;
    }

    public function getFirstForGroup(): int
    {
        $rawMatch = $this->base->matchOffset();
        if ($rawMatch->hasGroup($this->nameOrIndex)) {
            $group = $rawMatch->getGroupByteOffset($this->nameOrIndex);
            if ($group !== null) {
                return $group;
            }
        } else {
            if (!$this->groupVerifier->groupExists($this->nameOrIndex)) {
                throw new NonexistentGroupException($this->nameOrIndex);
            }
            if (!$rawMatch->matched()) {
                if ($this->isWholeMatch) {
                    throw SubjectNotMatchedException::forFirstOffset($this->base);
                } else {
                    throw SubjectNotMatchedException::forFirstGroupOffset($this->base, $this->nameOrIndex);
                }
            }
        }
        throw GroupNotMatchedException::forFirst($this->base, $this->nameOrIndex);
    }
}

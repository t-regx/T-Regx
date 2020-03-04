<?php
namespace TRegx\CleanRegex\Internal\GroupLimit;

use TRegx\CleanRegex\Exception\NonexistentGroupException;
use TRegx\CleanRegex\Internal\Match\Base\Base;
use TRegx\CleanRegex\Internal\Model\Matches\IRawMatchesOffset;

class GroupLimitAll
{
    /** @var Base */
    private $base;
    /** @var string|int */
    private $nameOrIndex;

    public function __construct(Base $base, $nameOrIndex)
    {
        $this->base = $base;
        $this->nameOrIndex = $nameOrIndex;
    }

    public function getAllForGroup(): IRawMatchesOffset
    {
        $rawMatches = $this->base->matchAllOffsets();
        if ($rawMatches->hasGroup($this->nameOrIndex)) {
            return $rawMatches;
        }
        throw new NonexistentGroupException($this->nameOrIndex);
    }
}

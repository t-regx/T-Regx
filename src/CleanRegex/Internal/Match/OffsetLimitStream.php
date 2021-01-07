<?php
namespace TRegx\CleanRegex\Internal\Match;

use TRegx\CleanRegex\Exception\NonexistentGroupException;
use TRegx\CleanRegex\Internal\Exception\NoFirstStreamException;
use TRegx\CleanRegex\Internal\Match\Base\Base;
use TRegx\CleanRegex\Internal\Match\Groups\Strategy\GroupVerifier;
use TRegx\CleanRegex\Internal\Match\Stream\Stream;
use TRegx\CleanRegex\Match\OffsetLimit;

class OffsetLimitStream implements Stream
{
    /** @var OffsetLimit */
    private $limit;
    /** @var GroupVerifier */
    private $groupVerifier;
    /** @var Base */
    private $base;
    /** @var string|int */
    private $nameOrIndex;

    public function __construct(OffsetLimit $limit, GroupVerifier $groupVerifier, Base $base, $nameOrIndex)
    {
        $this->limit = $limit;
        $this->groupVerifier = $groupVerifier;
        $this->base = $base;
        $this->nameOrIndex = $nameOrIndex;
    }

    public function all(): array
    {
        return $this->limit->all();
    }

    public function first(): int
    {
        $rawMatch = $this->base->matchOffset();
        if ($rawMatch->hasGroup($this->nameOrIndex)) {
            $group = $rawMatch->getGroupByteOffset($this->nameOrIndex);
            if ($group !== null) {
                return $group;
            }
            throw new NoFirstStreamException();
        }
        if ($this->groupVerifier->groupExists($this->nameOrIndex)) {
            throw new NoFirstStreamException();
        }
        throw new NonexistentGroupException($this->nameOrIndex);
    }

    public function firstKey(): int
    {
        $this->first(); // throw exception, if there is no group
        return 0;
    }
}

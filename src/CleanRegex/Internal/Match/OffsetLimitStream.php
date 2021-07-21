<?php
namespace TRegx\CleanRegex\Internal\Match;

use TRegx\CleanRegex\Exception\NonexistentGroupException;
use TRegx\CleanRegex\Internal\Exception\NoFirstStreamException;
use TRegx\CleanRegex\Internal\Match\Base\Base;
use TRegx\CleanRegex\Internal\Match\Stream\Stream;

class OffsetLimitStream implements Stream
{
    /** @var Base */
    private $base;
    /** @var string|int */
    private $nameOrIndex;
    /** @var GroupVerifier */
    private $groupVerifier;

    public function __construct(Base $base, $nameOrIndex, GroupVerifier $groupVerifier)
    {
        $this->base = $base;
        $this->nameOrIndex = $nameOrIndex;
        $this->groupVerifier = $groupVerifier;
    }

    public function all(): array
    {
        $matches = $this->base->matchAllOffsets();
        if (!$matches->hasGroup($this->nameOrIndex)) {
            throw new NonexistentGroupException($this->nameOrIndex);
        }
        return $matches->getLimitedGroupOffsets($this->nameOrIndex, -1);
    }

    public function first(): int
    {
        [$first, $firstKey] = $this->getFirstAndKey();
        return $first;
    }

    public function firstKey(): int
    {
        [$first, $firstKey] = $this->getFirstAndKey();
        return $firstKey;
    }

    private function getFirstAndKey(): array
    {
        $rawMatch = $this->base->matchOffset();
        if ($rawMatch->hasGroup($this->nameOrIndex)) {
            $group = $rawMatch->getGroupByteOffset($this->nameOrIndex);
            if ($group !== null) {
                return [$group, $rawMatch->getIndex()];
            }
            throw new NoFirstStreamException();
        }
        if ($this->groupVerifier->groupExists($this->nameOrIndex)) {
            throw new NoFirstStreamException();
        }
        throw new NonexistentGroupException($this->nameOrIndex);
    }
}

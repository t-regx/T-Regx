<?php
namespace TRegx\CleanRegex\Internal\OffsetLimit;

use InvalidArgumentException;
use TRegx\CleanRegex\Exception\NonexistentGroupException;
use TRegx\CleanRegex\Internal\Match\Base\Base;

class MatchOffsetLimitAll
{
    /** @var string|int */
    private $nameOrIndex;
    /** @var Base */
    private $base;

    public function __construct(Base $base, $nameOrIndex)
    {
        $this->base = $base;
        $this->nameOrIndex = $nameOrIndex;
    }

    public function getAllForGroup(int $limit, bool $allowNegative): array
    {
        $matches = $this->base->matchAllOffsets();
        if (!$matches->hasGroup($this->nameOrIndex)) {
            throw new NonexistentGroupException($this->nameOrIndex);
        }
        if (!$allowNegative && $limit < 0) {
            throw new InvalidArgumentException("Negative limit: $limit");
        }
        return $matches->getLimitedGroupOffsets($this->nameOrIndex, $limit);
    }
}

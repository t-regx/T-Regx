<?php
namespace TRegx\CleanRegex\Internal\GroupLimit;

use InvalidArgumentException;
use TRegx\CleanRegex\Exception\CleanRegex\NonexistentGroupException;
use TRegx\CleanRegex\Internal\Match\Base\Base;
use function array_slice;

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

    public function getAllForGroup(int $limit, bool $allowNegative): array
    {
        $rawMatches = $this->base->matchAll();
        if (!$rawMatches->hasGroup($this->nameOrIndex)) {
            throw new NonexistentGroupException($this->nameOrIndex);
        }
        if (!$allowNegative && $limit < 0) {
            throw new InvalidArgumentException("Negative limit $limit");
        }
        if ($limit === -1) {
            return $rawMatches->getGroupTexts($this->nameOrIndex);
        }
        return array_slice($rawMatches->getGroupTexts($this->nameOrIndex), 0, $limit);
    }
}

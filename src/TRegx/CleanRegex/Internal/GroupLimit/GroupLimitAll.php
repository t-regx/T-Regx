<?php
namespace TRegx\CleanRegex\Internal\GroupLimit;

use InvalidArgumentException;
use TRegx\CleanRegex\Exception\CleanRegex\NonexistentGroupException;
use TRegx\CleanRegex\Internal\Match\Adapter\Base;
use function array_key_exists;

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
        $matches = $this->base->matchAll();
        if (!$this->groupExistsIn($matches)) {
            throw new NonexistentGroupException($this->nameOrIndex);
        }
        if (!$allowNegative && $limit < 0) {
            throw new InvalidArgumentException("Negative limit $limit");
        }
        if ($limit === -1) {
            return $matches[$this->nameOrIndex];
        }
        return array_slice($matches[$this->nameOrIndex], 0, $limit);
    }

    private function groupExistsIn(array $matches): bool
    {
        return array_key_exists($this->nameOrIndex, $matches);
    }
}

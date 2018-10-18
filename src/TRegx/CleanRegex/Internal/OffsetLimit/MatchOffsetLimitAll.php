<?php
namespace TRegx\CleanRegex\Internal\OffsetLimit;

use InvalidArgumentException;
use TRegx\CleanRegex\Exception\CleanRegex\NonexistentGroupException;
use TRegx\CleanRegex\Internal\Grouper;
use TRegx\CleanRegex\Internal\Match\Adapter\Base;
use function array_key_exists;
use function array_map;
use function array_slice;

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
        if (!$this->groupExistsIn($matches)) {
            throw new NonexistentGroupException($this->nameOrIndex);
        }
        if (!$allowNegative && $limit < 0) {
            throw new InvalidArgumentException("Negative limit $limit");
        }
        return $this->mapToOffset($this->getLimitedMatches($limit, $matches));
    }

    private function groupExistsIn(array $matches): bool
    {
        return array_key_exists($this->nameOrIndex, $matches);
    }

    private function getLimitedMatches(int $limit, $matches)
    {
        $match = $matches[$this->nameOrIndex];
        if ($limit === -1) {
            return $match;
        }
        return array_slice($match, 0, $limit);
    }

    private function mapToOffset(array $matches): array
    {
        return array_map(function ($match) {
            return (new Grouper($match))->getOffset();
        }, $matches);
    }
}

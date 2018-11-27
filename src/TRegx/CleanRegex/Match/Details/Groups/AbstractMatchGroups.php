<?php
namespace TRegx\CleanRegex\Match\Details\Groups;

use TRegx\CleanRegex\Exception\CleanRegex\InternalCleanRegexException;
use TRegx\CleanRegex\Internal\Model\IRawMatchOffset;

abstract class AbstractMatchGroups implements MatchGroups
{
    /** @var IRawMatchOffset */
    protected $match;
    /** @var int */
    protected $index;

    protected function __construct(IRawMatchOffset $match)
    {
        $this->match = $match;
    }

    /**
     * @return (string|null)[]
     */
    public function texts(): array
    {
        return $this->sliceAndFilter($this->match->getGroupsTexts());
    }

    /**
     * @return (int|null)[]
     */
    public function offsets(): array
    {
        return $this->sliceAndFilter($this->match->getGroupsOffsets());
    }

    private function sliceAndFilter(array $valuesWithWhole): array
    {
        return $this->filterValues(array_slice($valuesWithWhole, 1));
    }

    private function filterValues(array $values): array
    {
        return array_filter($values, [$this, 'filter'], ARRAY_FILTER_USE_BOTH);
    }

    private function filter($value, $key): bool
    {
        if ((is_int($value) && $value > -1) || is_string($value) || is_null($value)) {
            return $this->filterGroupKey($key);
        }
        throw new InternalCleanRegexException();
    }

    protected abstract function filterGroupKey($nameOrIndex): bool;
}

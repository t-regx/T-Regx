<?php
namespace TRegx\CleanRegex\Match\Details\Groups;

use TRegx\CleanRegex\Exception\CleanRegex\InternalCleanRegexException;
use TRegx\CleanRegex\Internal\Model\RawMatchesOffset;

abstract class AbstractMatchGroups implements MatchGroups
{
    /** @var RawMatchesOffset */
    protected $matches;
    /** @var int */
    protected $index;

    protected function __construct(RawMatchesOffset $matches, int $index)
    {
        $this->matches = $matches;
        $this->index = $index;
    }

    /**
     * @return (string|null)[]
     */
    public function texts(): array
    {
        return $this->sliceAndFilter($this->matches->getGroupsTexts($this->index));
    }

    /**
     * @return (int|null)[]
     */
    public function offsets(): array
    {
        return $this->sliceAndFilter($this->matches->getGroupsOffsets($this->index));
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

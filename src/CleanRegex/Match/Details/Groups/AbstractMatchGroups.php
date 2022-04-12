<?php
namespace TRegx\CleanRegex\Match\Details\Groups;

use TRegx\CleanRegex\Exception\InternalCleanRegexException;
use TRegx\CleanRegex\Internal\Model\Match\GroupEntries;
use TRegx\CleanRegex\Internal\Offset\ByteOffset;
use TRegx\CleanRegex\Internal\Subject;

abstract class AbstractMatchGroups implements MatchGroups
{
    /** @var GroupEntries */
    private $entries;
    /** @var Subject */
    private $subject;

    public function __construct(GroupEntries $entries, Subject $subject)
    {
        $this->entries = $entries;
        $this->subject = $subject;
    }

    public function texts(): array
    {
        return $this->sliceAndFilter($this->entries->groupTexts());
    }

    public function offsets(): array
    {
        return \array_map(function (int $offset): int {
            return ByteOffset::toCharacterOffset($this->subject, $offset);
        }, $this->byteOffsets());
    }

    public function byteOffsets(): array
    {
        return $this->sliceAndFilter($this->entries->groupOffsets());
    }

    private function sliceAndFilter(array $valuesWithWhole): array
    {
        return $this->filterValues(\array_slice($valuesWithWhole, 1));
    }

    private function filterValues(array $values): array
    {
        return \array_filter($values, [$this, 'validateAndFilterGroupKey'], \ARRAY_FILTER_USE_BOTH);
    }

    private function validateAndFilterGroupKey($value, $key): bool
    {
        if ($this->isGroupValue($value)) {
            return $this->filterGroupKey($key);
        }
        // @codeCoverageIgnoreStart
        throw new InternalCleanRegexException();
        // @codeCoverageIgnoreEnd
    }

    private function isGroupValue($value): bool
    {
        if (\is_int($value) && $value > -1) {
            return true;
        }
        if (\is_string($value)) {
            return true;
        }
        return $value === null;
    }

    abstract protected function filterGroupKey($nameOrIndex): bool;
}

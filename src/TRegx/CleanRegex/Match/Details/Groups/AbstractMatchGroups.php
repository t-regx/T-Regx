<?php
namespace TRegx\CleanRegex\Match\Details\Groups;

use TRegx\CleanRegex\Exception\InternalCleanRegexException;
use TRegx\CleanRegex\Internal\ByteOffset;
use TRegx\CleanRegex\Internal\Model\Match\IRawMatchOffset;
use TRegx\CleanRegex\Internal\Subjectable;
use function array_slice;

abstract class AbstractMatchGroups implements MatchGroups
{
    /** @var IRawMatchOffset */
    protected $match;
    /** @var Subjectable */
    private $subjectable;

    public function __construct(IRawMatchOffset $match, Subjectable $subjectable)
    {
        $this->match = $match;
        $this->subjectable = $subjectable;
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
        return \array_map(function (int $offset) {
            return ByteOffset::toCharacterOffset($this->subjectable->getSubject(), $offset);
        }, $this->byteOffsets());
    }

    /**
     * @return (int|null)[]
     */
    public function byteOffsets()
    {
        return $this->sliceAndFilter($this->match->getGroupsOffsets());
    }

    private function sliceAndFilter(array $valuesWithWhole): array
    {
        return $this->filterValues(array_slice($valuesWithWhole, 1));
    }

    private function filterValues(array $values): array
    {
        return \array_filter($values, [$this, 'validateAndFilterGroupKey'], ARRAY_FILTER_USE_BOTH);
    }

    private function validateAndFilterGroupKey($value, $key): bool
    {
        if ((\is_int($value) && $value > -1) || \is_string($value) || $value === null) {
            return $this->filterGroupKey($key);
        }
        throw new InternalCleanRegexException();
    }

    abstract public function names(): array;

    abstract public function count(): int;

    abstract protected function filterGroupKey($nameOrIndex): bool;
}

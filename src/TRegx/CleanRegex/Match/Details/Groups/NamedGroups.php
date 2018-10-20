<?php
namespace TRegx\CleanRegex\Match\Details\Groups;

use TRegx\CleanRegex\Internal\Model\RawMatchesOffset;

class NamedGroups extends AbstractMatchGroups
{
    public function __construct(RawMatchesOffset $matches, int $index)
    {
        parent::__construct($matches, $index);
    }

    protected function filterGroupKey($nameOrIndex): bool
    {
        return is_string($nameOrIndex);
    }

    protected function sliceWholeMatch(array $matches): array
    {
        return $matches;
    }
}

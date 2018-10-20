<?php
namespace TRegx\CleanRegex\Match\Details\Groups;

use TRegx\CleanRegex\Internal\Model\RawMatchesOffset;

class IndexedGroups extends AbstractMatchGroups
{
    public function __construct(RawMatchesOffset $matches, int $index)
    {
        parent::__construct($matches, $index);
    }

    protected function filterGroupKey($nameOrIndex): bool
    {
        return is_int($nameOrIndex);
    }
}

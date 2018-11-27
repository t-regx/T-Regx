<?php
namespace TRegx\CleanRegex\Match\Details\Groups;

use TRegx\CleanRegex\Internal\Model\IRawMatchesOffset;

class IndexedGroups extends AbstractMatchGroups
{
    public function __construct(IRawMatchesOffset $matches, int $index)
    {
        parent::__construct($matches, $index);
    }

    protected function filterGroupKey($nameOrIndex): bool
    {
        return is_int($nameOrIndex);
    }
}

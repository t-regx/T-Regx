<?php
namespace TRegx\CleanRegex\Match\Details\Groups;

class IndexedGroups extends AbstractMatchGroups
{
    protected function filterGroupKey($nameOrIndex): bool
    {
        return is_int($nameOrIndex);
    }
}

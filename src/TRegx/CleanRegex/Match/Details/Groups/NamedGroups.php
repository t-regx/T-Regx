<?php
namespace TRegx\CleanRegex\Match\Details\Groups;

class NamedGroups extends AbstractMatchGroups
{
    protected function filterGroupKey($nameOrIndex): bool
    {
        return is_string($nameOrIndex);
    }
}

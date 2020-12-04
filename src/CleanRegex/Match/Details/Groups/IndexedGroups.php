<?php
namespace TRegx\CleanRegex\Match\Details\Groups;

use TRegx\CleanRegex\Internal\GroupNames;

class IndexedGroups extends AbstractMatchGroups
{
    protected function filterGroupKey($nameOrIndex): bool
    {
        return \is_int($nameOrIndex);
    }

    public function names(): array
    {
        return (new GroupNames($this->match))->groupNames();
    }

    public function count(): int
    {
        return \max(0, \count(\array_filter($this->match->getGroupKeys(), '\is_int')) - 1);
    }
}

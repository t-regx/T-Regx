<?php
namespace TRegx\CleanRegex\Match\Details\Groups;

class NamedGroups extends AbstractMatchGroups
{
    protected function filterGroupKey($nameOrIndex): bool
    {
        return \is_string($nameOrIndex);
    }

    public function names(): array
    {
        return \array_values(\array_filter($this->match->getGroupKeys(), '\is_string'));
    }

    public function count(): int
    {
        return \count(\array_filter($this->match->getGroupKeys(), '\is_string'));
    }
}

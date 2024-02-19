<?php
namespace Regex\Internal;

class Groups
{
    public GroupKeys $keys;
    private array $groupKeys;
    private GroupNames $groupNames;

    public function __construct(array $groupKeys)
    {
        $this->keys = new GroupKeys($groupKeys);
        $this->groupKeys = $groupKeys;
        $this->groupNames = new GroupNames($groupKeys);
    }

    public function names(): array
    {
        return $this->groupNames->names;
    }

    public function count(): int
    {
        return \count(\array_filter($this->groupKeys, '\is_int')) - 1;
    }
}

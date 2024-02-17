<?php
namespace Regex\Internal;

class GroupKeys
{
    private array $groupKeys;

    public function __construct(array $groupKeys)
    {
        $this->groupKeys = $groupKeys;
    }

    public function groupExists(GroupKey $group): bool
    {
        return \in_array($group->nameOrIndex, $this->groupKeys, true);
    }

    public function unambiguousIndex(GroupKey $group): int
    {
        if (\is_string($group->nameOrIndex)) {
            return $this->correspondingGroupIndex($group->nameOrIndex);
        }
        return $group->nameOrIndex;
    }

    private function correspondingGroupIndex(string $name): int
    {
        $index = \array_search($name, $this->groupKeys, true);
        return $this->groupKeys[$index + 1];
    }
}

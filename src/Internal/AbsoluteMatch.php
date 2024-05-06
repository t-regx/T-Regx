<?php
namespace Regex\Internal;

class AbsoluteMatch
{
    private GroupKeys $groups;
    private array $match;

    public function __construct(GroupKeys $groupKeys, array $match)
    {
        $this->groups = $groupKeys;
        $this->match = $match;
    }

    public function groupExists(GroupKey $group): bool
    {
        return $this->groups->groupExists($group);
    }

    public function groupMatched(GroupKey $group): bool
    {
        if (\array_key_exists($group->nameOrIndex, $this->match)) {
            return $this->match[$this->groups->unambiguousIndex($group)][1] !== -1;
        }
        return false;
    }

    public function groupText(GroupKey $group): string
    {
        return $this->match[$this->groups->unambiguousIndex($group)][0];
    }

    public function groupOffset(GroupKey $group): int
    {
        return $this->match[$group->nameOrIndex][1];
    }
}

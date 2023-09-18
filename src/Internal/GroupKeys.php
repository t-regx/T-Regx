<?php
namespace Regex\Internal;

class GroupKeys
{
    private DelimitedExpression $expression;

    public function __construct(DelimitedExpression $expression)
    {
        $this->expression = $expression;
    }

    public function groupExists(GroupKey $group): bool
    {
        return \in_array($group->nameOrIndex, $this->expression->groupKeys, true);
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
        $index = \array_search($name, $this->expression->groupKeys, true);
        return $this->expression->groupKeys[$index + 1];
    }
}

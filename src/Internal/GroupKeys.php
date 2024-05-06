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
}

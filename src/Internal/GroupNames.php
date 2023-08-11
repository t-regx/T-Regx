<?php
namespace Regex\Internal;

class GroupNames
{
    /** @var string[]|null[] */
    public array $names;

    public function __construct(DelimitedExpression $expression)
    {
        $this->names = $this->groupNames(\array_slice($expression->groupKeys, 1));
    }

    private function groupNames(array $groupKeys): array
    {
        $iterator = new \ArrayIterator($groupKeys);
        $names = [];
        foreach ($iterator as $groupKey) {
            if (\is_string($groupKey)) {
                $names[] = $groupKey;
                $iterator->next();
            } else {
                $names[] = null;
            }
        }
        return $names;
    }
}

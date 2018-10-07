<?php
namespace TRegx\CleanRegex\Match\Details\Groups;

class NamedGroups extends AbstractMatchGroups
{
    public function __construct(array $matches, int $index)
    {
        parent::__construct($matches, $index);
    }

    protected function filterGroupKey($groupIndexOrName): bool
    {
        return is_string($groupIndexOrName);
    }

    protected function sliceWholeMatch(array $matches): array
    {
        return $matches;
    }
}

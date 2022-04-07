<?php
namespace TRegx\CleanRegex\Match\Details\Groups;

use TRegx\CleanRegex\Internal\Model\GroupKeys;
use TRegx\CleanRegex\Internal\Model\Match\GroupEntries;
use TRegx\CleanRegex\Internal\Subject;

class NamedGroups extends AbstractMatchGroups
{
    /** @var GroupKeys */
    private $groupKeys;

    public function __construct(GroupKeys $groupKeys, GroupEntries $entries, Subject $subject)
    {
        parent::__construct($entries, $subject);
        $this->groupKeys = $groupKeys;
    }

    protected function filterGroupKey($nameOrIndex): bool
    {
        return \is_string($nameOrIndex);
    }

    public function names(): array
    {
        return \array_values(\array_filter($this->groupKeys->getGroupKeys(), '\is_string'));
    }

    public function count(): int
    {
        return \count(\array_filter($this->groupKeys->getGroupKeys(), '\is_string'));
    }
}

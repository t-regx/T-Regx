<?php
namespace TRegx\CleanRegex\Match\Details\Groups;

use TRegx\CleanRegex\Internal\GroupNames;
use TRegx\CleanRegex\Internal\Match\Details\GroupsCount;
use TRegx\CleanRegex\Internal\Model\GroupKeys;
use TRegx\CleanRegex\Internal\Model\Match\GroupEntries;
use TRegx\CleanRegex\Internal\Subject;

class IndexedGroups extends AbstractMatchGroups
{
    /** @var GroupNames */
    private $groupNames;
    /** @var GroupsCount */
    private $groupsCount;

    public function __construct(GroupKeys $groupKeys, GroupEntries $entries, Subject $subject)
    {
        parent::__construct($entries, $subject);
        $this->groupNames = new GroupNames($groupKeys);
        $this->groupsCount = new GroupsCount($groupKeys);
    }

    protected function filterGroupKey($nameOrIndex): bool
    {
        return \is_int($nameOrIndex);
    }

    public function names(): array
    {
        return $this->groupNames->groupNames();
    }

    public function count(): int
    {
        return $this->groupsCount->groupsCount();
    }
}

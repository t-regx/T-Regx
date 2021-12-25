<?php
namespace TRegx\CleanRegex\Match\Details\Groups;

use TRegx\CleanRegex\Internal\GroupNames;
use TRegx\CleanRegex\Internal\Match\Details\GroupsCount;
use TRegx\CleanRegex\Internal\Model\GroupAware;
use TRegx\CleanRegex\Internal\Model\Match\GroupEntries;
use TRegx\CleanRegex\Internal\Subject;

class IndexedGroups extends AbstractMatchGroups
{
    /** @var GroupNames */
    private $groupNames;
    /** @var GroupsCount */
    private $groupsCount;

    public function __construct(GroupAware $groupAware, GroupEntries $entries, Subject $subject)
    {
        parent::__construct($entries, $subject);
        $this->groupNames = new GroupNames($groupAware);
        $this->groupsCount = new GroupsCount($groupAware);
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

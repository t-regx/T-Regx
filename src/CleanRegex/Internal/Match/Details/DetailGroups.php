<?php
namespace TRegx\CleanRegex\Internal\Match\Details;

use TRegx\CleanRegex\Internal\GroupNames;
use TRegx\CleanRegex\Internal\Model\GroupAware;
use TRegx\CleanRegex\Internal\Model\Match\UsedInCompositeGroups;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\CleanRegex\Match\Details\Groups\IndexedGroups;
use TRegx\CleanRegex\Match\Details\Groups\NamedGroups;

class DetailGroups
{
    /** @var IndexedGroups */
    private $indexedGroups;
    /** @var NamedGroups */
    private $namedGroups;
    /** @var GroupNames */
    private $groupNames;
    /** @var GroupsCount */
    private $groupsCount;

    public function __construct(GroupAware $groupAware, UsedInCompositeGroups $usedInCompo, Subject $subject)
    {
        $this->indexedGroups = new IndexedGroups($groupAware, $usedInCompo, $subject);
        $this->namedGroups = new NamedGroups($groupAware, $usedInCompo, $subject);
        $this->groupNames = new GroupNames($groupAware);
        $this->groupsCount = new GroupsCount($groupAware);
    }

    public function indexedGroups(): IndexedGroups
    {
        return $this->indexedGroups;
    }

    public function namedGroups(): NamedGroups
    {
        return $this->namedGroups;
    }

    public function groupNames(): array
    {
        return $this->groupNames->groupNames();
    }

    public function groupsCount(): int
    {
        return $this->groupsCount->groupsCount();
    }
}

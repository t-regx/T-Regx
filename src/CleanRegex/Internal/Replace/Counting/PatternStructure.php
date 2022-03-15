<?php
namespace TRegx\CleanRegex\Internal\Replace\Counting;

use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\GroupNames;
use TRegx\CleanRegex\Internal\Match\Details\GroupsCount;
use TRegx\CleanRegex\Internal\Model\GroupAware;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\CleanRegex\Match\Details\Structure;

class PatternStructure implements Structure
{
    /** @var Subject */
    private $subject;
    /** @var GroupNames */
    private $groupNames;
    /** @var GroupsCount */
    private $groupsCount;
    /** @var GroupAware */
    private $groupAware;

    public function __construct(Subject $subject, GroupAware $groupAware)
    {
        $this->subject = $subject;
        $this->groupNames = new GroupNames($groupAware);
        $this->groupsCount = new GroupsCount($groupAware);
        $this->groupAware = $groupAware;
    }

    public function subject(): string
    {
        return $this->subject;
    }

    public function groupNames(): array
    {
        return $this->groupNames->groupNames();
    }

    public function groupsCount(): int
    {
        return $this->groupsCount->groupsCount();
    }

    public function hasGroup($nameOrIndex): bool
    {
        GroupKey::of($nameOrIndex);
        return $this->groupAware->hasGroup($nameOrIndex);
    }
}

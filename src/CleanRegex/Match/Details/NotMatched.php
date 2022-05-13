<?php
namespace TRegx\CleanRegex\Match\Details;

use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\GroupNames;
use TRegx\CleanRegex\Internal\Model\GroupAware;
use TRegx\CleanRegex\Internal\Subject;

/**
 * @deprecated
 */
class NotMatched implements Structure
{
    /** @var GroupAware */
    private $groupAware;
    /** @var Subject */
    private $subject;
    /** @var GroupNames */
    private $groupNames;

    public function __construct(GroupAware $groupAware, Subject $subject)
    {
        $this->groupAware = $groupAware;
        $this->subject = $subject;
        $this->groupNames = new GroupNames($groupAware);
    }

    public function subject(): string
    {
        return $this->subject;
    }

    /**
     * @return string[]
     */
    public function groupNames(): array
    {
        return $this->groupNames->groupNames();
    }

    public function groupsCount(): int
    {
        $indexedGroups = \array_filter($this->groupAware->getGroupKeys(), '\is_int');
        return \count($indexedGroups) - 1;
    }

    /**
     * @param string|int $nameOrIndex
     * @return bool
     */
    public function hasGroup($nameOrIndex): bool
    {
        return $this->groupAware->hasGroup(GroupKey::of($nameOrIndex));
    }
}

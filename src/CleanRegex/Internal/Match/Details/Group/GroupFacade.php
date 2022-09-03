<?php
namespace TRegx\CleanRegex\Internal\Match\Details\Group;

use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\GroupKey\Signatures;
use TRegx\CleanRegex\Internal\Pcre\Legacy\MatchAllFactory;
use TRegx\CleanRegex\Internal\Pcre\Legacy\UsedForGroup;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\CleanRegex\Match\Group;

class GroupFacade
{
    /** @var Subject */
    private $subject;
    /** @var GroupHandle */
    private $groupHandle;
    /** @var MatchAllFactory */
    private $allFactory;
    /** @var Signatures */
    private $signatures;

    public function __construct(Subject $subject, MatchAllFactory $allFactory, GroupHandle $groupHandle, Signatures $signatures)
    {
        $this->subject = $subject;
        $this->groupHandle = $groupHandle;
        $this->allFactory = $allFactory;
        $this->signatures = $signatures;
    }

    public function createGroup(GroupKey $group, UsedForGroup $forGroup): Group
    {
        if ($forGroup->isGroupMatched($this->groupHandle->groupHandle($group))) {
            return $this->createdMatched($group, $this->groupEntry($forGroup, $group));
        }
        return $this->createUnmatched($group);
    }

    private function createdMatched(GroupKey $group, GroupEntry $groupEntry): MatchedGroup
    {
        return new MatchedGroup($this->subject, $this->createGroupDetails($group), $groupEntry);
    }

    private function createUnmatched(GroupKey $group): NotMatchedGroup
    {
        return new NotMatchedGroup($this->subject, $this->createGroupDetails($group));
    }

    private function createGroupDetails(GroupKey $group): GroupDetails
    {
        return new GroupDetails($this->groupHandle, $group, $this->allFactory, $this->signatures->signature($group));
    }

    private function groupEntry(UsedForGroup $forGroup, GroupKey $group): GroupEntry
    {
        [$text, $offset] = $forGroup->getGroupTextAndOffset($this->groupHandle->groupHandle($group));
        return new GroupEntry($text, $offset, $this->subject);
    }
}

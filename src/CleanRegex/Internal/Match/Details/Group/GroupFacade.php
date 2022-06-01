<?php
namespace TRegx\CleanRegex\Internal\Match\Details\Group;

use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\GroupKey\Signatures;
use TRegx\CleanRegex\Internal\Model\Entry;
use TRegx\CleanRegex\Internal\Pcre\Legacy\MatchAllFactory;
use TRegx\CleanRegex\Internal\Pcre\Legacy\UsedForGroup;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\CleanRegex\Match\Details\Group\Group;
use TRegx\CleanRegex\Match\Details\Group\MatchedGroup;
use TRegx\CleanRegex\Match\Details\Group\NotMatchedGroup;

class GroupFacade
{
    /** @var Subject */
    private $subject;
    /** @var GroupHandle */
    private $groupHandle;
    /** @var GroupFactoryStrategy */
    private $factoryStrategy;
    /** @var MatchAllFactory */
    private $allFactory;
    /** @var Signatures */
    private $signatures;

    public function __construct(Subject              $subject,
                                GroupFactoryStrategy $factoryStrategy,
                                MatchAllFactory      $allFactory,
                                GroupHandle          $groupHandle,
                                Signatures           $signatures)
    {
        $this->subject = $subject;
        $this->groupHandle = $groupHandle;
        $this->factoryStrategy = $factoryStrategy;
        $this->allFactory = $allFactory;
        $this->signatures = $signatures;
    }

    public function createGroup(GroupKey $group, UsedForGroup $forGroup, Entry $entry): Group
    {
        if ($forGroup->isGroupMatched($this->groupHandle->groupHandle($group))) {
            return $this->createdMatched($group, $this->groupEntry($forGroup, $group), $entry);
        }
        return $this->createUnmatched($group);
    }

    private function createdMatched(GroupKey $group, GroupEntry $groupEntry, Entry $entry): MatchedGroup
    {
        return $this->factoryStrategy->matched($this->subject, $this->createGroupDetails($group), $groupEntry, new SubstitutedGroup($entry, $groupEntry));
    }

    private function createUnmatched(GroupKey $group): NotMatchedGroup
    {
        return $this->factoryStrategy->notMatched($this->subject, $this->createGroupDetails($group));
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

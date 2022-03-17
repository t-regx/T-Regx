<?php
namespace TRegx\CleanRegex\Internal\Match\Details\Group;

use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\GroupKey\Signatures;
use TRegx\CleanRegex\Internal\Match\Details\Group\Handle\GroupHandle;
use TRegx\CleanRegex\Internal\Match\MatchAll\MatchAllFactory;
use TRegx\CleanRegex\Internal\Model\Match\Entry;
use TRegx\CleanRegex\Internal\Model\Match\RawMatchesOffset;
use TRegx\CleanRegex\Internal\Model\Match\UsedForGroup;
use TRegx\CleanRegex\Internal\Model\RawMatchesToMatchAdapter;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\CleanRegex\Match\Details\Group\Group;
use TRegx\CleanRegex\Match\Details\Group\MatchedGroup;
use TRegx\CleanRegex\Match\Details\Group\NotMatchedGroup;
use TRegx\CleanRegex\Match\Details\NotMatched;

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
    /** @var NotMatched */
    private $notMatched;

    public function __construct(Subject              $subject,
                                GroupFactoryStrategy $factoryStrategy,
                                MatchAllFactory      $allFactory,
                                NotMatched           $notMatched,
                                GroupHandle          $groupHandle,
                                Signatures           $signatures)
    {
        $this->subject = $subject;
        $this->groupHandle = $groupHandle;
        $this->factoryStrategy = $factoryStrategy;
        $this->allFactory = $allFactory;
        $this->notMatched = $notMatched;
        $this->signatures = $signatures;
    }

    public function createGroups(GroupKey $groupKey, RawMatchesOffset $matches): array
    {
        $groupIndexes = \array_keys($matches->getGroupTextAndOffsetAll($this->groupHandle->groupHandle($groupKey)));
        $result = [];
        foreach ($groupIndexes as $index) {
            $match = new RawMatchesToMatchAdapter($matches, $index);
            $result[$index] = $this->createGroup($groupKey, $match, $match);
        }
        return $result;
    }

    public function createGroup(GroupKey $group, UsedForGroup $forGroup, Entry $entry): Group
    {
        if ($forGroup->isGroupMatched($this->groupHandle->groupHandle($group))) {
            [$text, $offset] = $forGroup->getGroupTextAndOffset($this->groupHandle->groupHandle($group));
            return $this->createdMatched($group, new GroupEntry($text, $offset, $this->subject), $entry);
        }
        return $this->createUnmatched($group);
    }

    private function createdMatched(GroupKey $group, GroupEntry $groupEntry, Entry $entry): MatchedGroup
    {
        return $this->factoryStrategy->matched($this->subject, $this->createGroupDetails($group), $groupEntry, new SubstitutedGroup($entry, $groupEntry));
    }

    private function createUnmatched(GroupKey $group): NotMatchedGroup
    {
        return $this->factoryStrategy->notMatched($this->subject, $this->createGroupDetails($group), $this->notMatched);
    }

    private function createGroupDetails(GroupKey $group): GroupDetails
    {
        return new GroupDetails($this->signatures->signature($group), $group, $this->allFactory);
    }
}

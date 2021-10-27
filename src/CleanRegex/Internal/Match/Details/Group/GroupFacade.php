<?php
namespace TRegx\CleanRegex\Internal\Match\Details\Group;

use Generator;
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
    /** @var GroupEntryFactory */
    private $entryFactory;

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
        $this->entryFactory = new GroupEntryFactory($this->subject, $this->groupHandle);
    }

    public function createGroups(GroupKey $group, RawMatchesOffset $matches): array
    {
        return \iterator_to_array($this->groups($group, $matches->getGroupTextAndOffsetAll($this->groupHandle->groupHandle($group)), $matches));
    }

    private function groups(GroupKey $groupKey, array $group, RawMatchesOffset $matches): Generator
    {
        foreach ($group as $index => [$text, $offset]) {
            $match = new RawMatchesToMatchAdapter($matches, $index);
            try {
                yield $index => $this->createdMatched($groupKey, $this->entryFactory->groupEntry($groupKey, $match), $match);
            } catch (UnmatchedGroupException $exception) {
                yield $index => $this->createUnmatched($groupKey);
            }
        }
    }

    public function createGroup(GroupKey $group, UsedForGroup $forGroup, Entry $entry): Group
    {
        try {
            return $this->createdMatched($group, $this->entryFactory->groupEntry($group, $forGroup), $entry);
        } catch (UnmatchedGroupException $exception) {
            return $this->createUnmatched($group);
        }
    }

    private function createdMatched(GroupKey $group, GroupEntry $groupEntry, Entry $entry): MatchedGroup
    {
        return $this->factoryStrategy->matched(
            $this->subject,
            $this->createGroupDetails($group),
            $groupEntry,
            new SubstitutedGroup($entry, $groupEntry));
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

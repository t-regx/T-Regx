<?php
namespace TRegx\CleanRegex\Internal\Match\Details\Group;

use Generator;
use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\CleanRegex\Internal\Exception\Messages\Group\GroupMessage;
use TRegx\CleanRegex\Internal\Factory\Optional\NotMatchedOptionalWorker;
use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\GroupKey\Signatures;
use TRegx\CleanRegex\Internal\Match\Details\Group\Handle\GroupHandle;
use TRegx\CleanRegex\Internal\Match\MatchAll\MatchAllFactory;
use TRegx\CleanRegex\Internal\Model\Match\MatchEntry;
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

    public function createGroups(GroupKey $group, RawMatchesOffset $matches): array
    {
        return \iterator_to_array($this->groups($group, $matches->getGroupTextAndOffsetAll($this->groupHandle->groupHandle($group)), $matches));
    }

    private function groups(GroupKey $groupKey, array $group, RawMatchesOffset $matches): Generator
    {
        foreach ($group as $index => [$text, $offset]) {
            $match = new RawMatchesToMatchAdapter($matches, $index);
            if ($match->isGroupMatched($this->groupHandle->groupHandle($groupKey))) {
                yield $index => $this->createdMatched($groupKey, $match, $text, $offset);
            } else {
                yield $index => $this->createUnmatched($groupKey);
            }
        }
    }

    public function createGroup(GroupKey $group, UsedForGroup $forGroup, MatchEntry $entry): Group
    {
        if ($forGroup->isGroupMatched($this->groupHandle->groupHandle($group))) {
            [$text, $offset] = $forGroup->getGroupTextAndOffset($this->groupHandle->groupHandle($group));
            return $this->createdMatched($group, $entry, $text, $offset);
        }
        return $this->createUnmatched($group);
    }

    private function createdMatched(GroupKey $group, MatchEntry $entry, string $text, int $offset): MatchedGroup
    {
        $groupEntry = new GroupEntry($text, $offset, $this->subject);
        return $this->factoryStrategy->createMatched(
            $this->subject,
            $this->createGroupDetails($group),
            $groupEntry,
            new SubstitutedGroup($entry, $groupEntry));
    }

    private function createUnmatched(GroupKey $group): NotMatchedGroup
    {
        return $this->factoryStrategy->createUnmatched(
            $this->subject,
            $this->createGroupDetails($group),
            new NotMatchedOptionalWorker(
                new GroupMessage($group),
                $this->subject,
                $this->notMatched,
                GroupNotMatchedException::class));
    }

    private function createGroupDetails(GroupKey $group): GroupDetails
    {
        return new GroupDetails($this->signatures->signature($group), $group, $this->allFactory);
    }
}

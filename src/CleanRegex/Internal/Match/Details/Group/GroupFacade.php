<?php
namespace TRegx\CleanRegex\Internal\Match\Details\Group;

use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\CleanRegex\Internal\Exception\Messages\Group\GroupMessage;
use TRegx\CleanRegex\Internal\Factory\GroupExceptionFactory;
use TRegx\CleanRegex\Internal\Factory\Optional\NotMatchedOptionalWorker;
use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\GroupKey\Signatures;
use TRegx\CleanRegex\Internal\Match\Details\Group\Handle\GroupHandle;
use TRegx\CleanRegex\Internal\Match\MatchAll\MatchAllFactory;
use TRegx\CleanRegex\Internal\Model\Match\MatchEntry;
use TRegx\CleanRegex\Internal\Model\Match\RawMatchesOffset;
use TRegx\CleanRegex\Internal\Model\Match\UsedForGroup;
use TRegx\CleanRegex\Internal\Model\RawMatchesToMatchAdapter;
use TRegx\CleanRegex\Internal\Subjectable;
use TRegx\CleanRegex\Match\Details\Group\Group;
use TRegx\CleanRegex\Match\Details\Group\MatchedGroup;
use TRegx\CleanRegex\Match\Details\Group\NotMatchedGroup;
use TRegx\CleanRegex\Match\Details\NotMatched;

class GroupFacade
{
    /** @var Subjectable */
    private $subject;
    /** @var GroupHandle */
    private $groupHandle;
    /** @var GroupFactoryStrategy */
    private $factoryStrategy;
    /** @var MatchAllFactory */
    private $allFactory;
    /** @var GroupKey */
    private $groupId;
    /** @var Signatures */
    private $signatures;
    /** @var NotMatched */
    private $notMatched;

    public function __construct(Subjectable          $subject,
                                GroupKey             $groupId,
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
        $this->groupId = $groupId;
        $this->notMatched = $notMatched;
        $this->signatures = $signatures;
    }

    /**
     * @param RawMatchesOffset $matches
     * @return Group[]
     */
    public function createGroups(RawMatchesOffset $matches): array
    {
        $matchObjects = [];
        foreach ($matches->getGroupTextAndOffsetAll($this->directIdentifier()) as $index => $firstWhole) {
            $match = new RawMatchesToMatchAdapter($matches, $index);
            if ($match->isGroupMatched($this->directIdentifier())) {
                $matchObjects[$index] = $this->createdMatched($match, ...$firstWhole);
            } else {
                $matchObjects[$index] = $this->createUnmatched();
            }
        }
        return $matchObjects;
    }

    public function createGroup(UsedForGroup $forGroup, MatchEntry $entry): Group
    {
        if ($forGroup->isGroupMatched($this->directIdentifier())) {
            [$text, $offset] = $forGroup->getGroupTextAndOffset($this->directIdentifier());
            return $this->createdMatched($entry, $text, $offset);
        }
        return $this->createUnmatched();
    }

    private function createdMatched(MatchEntry $entry, string $text, int $offset): MatchedGroup
    {
        $groupEntry = new GroupEntry($text, $offset, $this->subject);
        return $this->factoryStrategy->createMatched(
            $this->subject,
            $this->createGroupDetails(),
            $groupEntry,
            new SubstitutedGroup($entry, $groupEntry));
    }

    private function createUnmatched(): NotMatchedGroup
    {
        return $this->factoryStrategy->createUnmatched(
            $this->createGroupDetails(),
            new GroupExceptionFactory($this->subject, $this->groupId),
            new NotMatchedOptionalWorker(
                new GroupMessage($this->groupId),
                $this->subject,
                $this->notMatched,
                GroupNotMatchedException::class),
            $this->subject->getSubject());
    }

    private function createGroupDetails(): GroupDetails
    {
        return new GroupDetails($this->signatures->signature($this->groupId), $this->groupId, $this->allFactory);
    }

    /**
     * @return string|int
     */
    private function directIdentifier()
    {
        return $this->groupHandle->groupHandle($this->groupId);
    }
}

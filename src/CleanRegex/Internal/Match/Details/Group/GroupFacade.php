<?php
namespace TRegx\CleanRegex\Internal\Match\Details\Group;

use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\CleanRegex\Internal\Exception\Messages\Group\GroupMessage;
use TRegx\CleanRegex\Internal\Factory\GroupExceptionFactory;
use TRegx\CleanRegex\Internal\Factory\Optional\NotMatchedOptionalWorker;
use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\GroupKey\GroupSignature;
use TRegx\CleanRegex\Internal\GroupNameIndexAssign;
use TRegx\CleanRegex\Internal\Match\Details\Group\Handle\GroupHandle;
use TRegx\CleanRegex\Internal\Match\MatchAll\MatchAllFactory;
use TRegx\CleanRegex\Internal\Model\GroupAware;
use TRegx\CleanRegex\Internal\Model\Match\IRawMatchOffset;
use TRegx\CleanRegex\Internal\Model\Match\MatchEntry;
use TRegx\CleanRegex\Internal\Model\Match\RawMatchesOffset;
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
    /** @var int */
    private $index;
    /** @var string|null */
    private $name;
    /** @var GroupFactoryStrategy */
    private $factoryStrategy;
    /** @var MatchAllFactory */
    private $allFactory;
    /** @var GroupKey */
    private $groupId;

    public function __construct(GroupAware           $groupAware,
                                Subjectable          $subject,
                                GroupKey             $groupId,
                                GroupFactoryStrategy $factoryStrategy,
                                MatchAllFactory      $allFactory,
                                GroupHandle          $groupHandle)
    {
        $this->subject = $subject;
        $this->groupHandle = $groupHandle;
        [$this->name, $this->index] = (new GroupNameIndexAssign($groupAware, $allFactory))->getNameAndIndex($groupId);
        $this->factoryStrategy = $factoryStrategy;
        $this->allFactory = $allFactory;
        $this->groupId = $groupId;
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
                $matchObjects[$index] = $this->createUnmatched($match);
            }
        }
        return $matchObjects;
    }

    public function createGroup(IRawMatchOffset $match): Group
    {
        if ($match->isGroupMatched($this->directIdentifier())) {
            [$text, $offset] = $match->getGroupTextAndOffset($this->directIdentifier());
            return $this->createdMatched($match, $text, $offset);
        }
        return $this->createUnmatched($match);
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

    private function createUnmatched(IRawMatchOffset $match): NotMatchedGroup
    {
        return $this->factoryStrategy->createUnmatched(
            $this->createGroupDetails(),
            new GroupExceptionFactory($this->subject, $this->groupId),
            new NotMatchedOptionalWorker(
                new GroupMessage($this->groupId),
                $this->subject,
                new NotMatched($match, $this->subject),
                GroupNotMatchedException::class),
            $this->subject->getSubject()
        );
    }

    private function createGroupDetails(): GroupDetails
    {
        return new GroupDetails(new GroupSignature($this->index, $this->name), $this->groupId, $this->allFactory);
    }

    /**
     * @return string|int
     */
    private function directIdentifier()
    {
        return $this->groupHandle->groupHandle($this->groupId);
    }
}

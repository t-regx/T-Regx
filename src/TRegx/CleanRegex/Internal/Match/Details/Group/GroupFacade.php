<?php
namespace TRegx\CleanRegex\Internal\Match\Details\Group;

use TRegx\CleanRegex\Internal\Exception\Messages\Group\GroupMessage;
use TRegx\CleanRegex\Internal\Factory\GroupExceptionFactory;
use TRegx\CleanRegex\Internal\Factory\NotMatchedOptionalWorker;
use TRegx\CleanRegex\Internal\GroupNameIndexAssign;
use TRegx\CleanRegex\Internal\Match\MatchAll\MatchAllFactory;
use TRegx\CleanRegex\Internal\Model\Adapter\RawMatchesToMatchAdapter;
use TRegx\CleanRegex\Internal\Model\IRawWithGroups;
use TRegx\CleanRegex\Internal\Model\Match\IRawMatchOffset;
use TRegx\CleanRegex\Internal\Model\Matches\RawMatchesOffset;
use TRegx\CleanRegex\Internal\Subjectable;
use TRegx\CleanRegex\Match\Details\Group\MatchedGroup;
use TRegx\CleanRegex\Match\Details\Group\MatchGroup;
use TRegx\CleanRegex\Match\Details\Group\NotMatchedGroup;
use TRegx\CleanRegex\Match\Details\NotMatched;

class GroupFacade
{
    /** @var Subjectable */
    private $subject;
    /** @var string|int */
    private $usedIdentifier;
    /** @var int */
    private $index;
    /** @var string|null */
    private $name;
    /** @var GroupFactoryStrategy */
    private $factoryStrategy;
    /** @var MatchAllFactory */
    private $allFactory;

    public function __construct(IRawWithGroups $groupAssignMatch,
                                Subjectable $subject,
                                $group,
                                GroupFactoryStrategy $factoryStrategy,
                                MatchAllFactory $allFactory)
    {
        $this->subject = $subject;
        $this->usedIdentifier = $group;
        [$this->name, $this->index] = (new GroupNameIndexAssign($groupAssignMatch, $allFactory))->getNameAndIndex($group);
        $this->factoryStrategy = $factoryStrategy;
        $this->allFactory = $allFactory;
    }

    /**
     * @param RawMatchesOffset $matches
     * @return MatchGroup[]
     */
    public function createGroups(RawMatchesOffset $matches): array
    {
        $matchObjects = [];
        foreach ($matches->getGroupTextAndOffsetAll($this->index) as $index => $firstWhole) {
            $match = new RawMatchesToMatchAdapter($matches, $index);
            if ($match->isGroupMatched($this->index)) {
                $matchObjects[] = $this->createdMatched($match, ...$firstWhole);
            } else {
                $matchObjects[] = $this->createUnmatched($match);
            }
        }
        return $matchObjects;
    }

    public function createGroup(IRawMatchOffset $match): MatchGroup
    {
        if ($match->isGroupMatched($this->index)) {
            return $this->createdMatched($match, ...$match->getGroupTextAndOffset($this->index));
        }
        return $this->createUnmatched($match);
    }

    private function createdMatched(IRawMatchOffset $match, string $text, int $offset): MatchedGroup
    {
        return $this->factoryStrategy->createMatched(
            $match,
            $this->createGroupDetails(),
            new MatchedGroupOccurrence($text, $offset, $this->subject));
    }

    private function createUnmatched(IRawMatchOffset $match): NotMatchedGroup
    {
        return $this->factoryStrategy->createUnmatched(
            $this->createGroupDetails(),
            new GroupExceptionFactory($this->subject, $this->usedIdentifier),
            new NotMatchedOptionalWorker(
                new GroupMessage($this->usedIdentifier),
                $this->subject,
                new NotMatched($match, $this->subject)
            )
        );
    }

    private function createGroupDetails(): GroupDetails
    {
        return new GroupDetails($this->name, $this->index, $this->usedIdentifier, $this->allFactory);
    }
}

<?php
namespace TRegx\CleanRegex\Internal\Match\Details\Group;

use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\CleanRegex\Internal\Exception\Messages\Group\GroupMessage;
use TRegx\CleanRegex\Internal\Factory\GroupExceptionFactory;
use TRegx\CleanRegex\Internal\Factory\Optional\NotMatchedOptionalWorker;
use TRegx\CleanRegex\Internal\GroupNameIndexAssign;
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
    /** @var string|int */
    protected $usedIdentifier;
    /** @var int */
    private $index;
    /** @var string|null */
    private $name;
    /** @var GroupFactoryStrategy */
    private $factoryStrategy;
    /** @var MatchAllFactory */
    private $allFactory;

    public function __construct(GroupAware $groupAware,
                                Subjectable $subject,
                                $group,
                                GroupFactoryStrategy $factoryStrategy,
                                MatchAllFactory $allFactory)
    {
        $this->subject = $subject;
        $this->usedIdentifier = $group;
        [$this->name, $this->index] = (new GroupNameIndexAssign($groupAware, $allFactory))->getNameAndIndex($group);
        $this->factoryStrategy = $factoryStrategy;
        $this->allFactory = $allFactory;
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
            return $this->createdMatched($match, ...$match->getGroupTextAndOffset($this->directIdentifier()));
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
            new GroupExceptionFactory($this->subject, $this->usedIdentifier),
            new NotMatchedOptionalWorker(
                new GroupMessage($this->usedIdentifier),
                $this->subject,
                new NotMatched($match, $this->subject),
                GroupNotMatchedException::class),
            $this->subject->getSubject()
        );
    }

    private function createGroupDetails(): GroupDetails
    {
        return new GroupDetails($this->name, $this->index, $this->usedIdentifier, $this->allFactory);
    }

    /**
     * @return string|int
     */
    protected function directIdentifier()
    {
        return $this->index; // when index is used, then compiled (pattern) group is used
    }
}

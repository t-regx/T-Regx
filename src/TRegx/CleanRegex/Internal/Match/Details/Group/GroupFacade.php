<?php
namespace TRegx\CleanRegex\Internal\Match\Details\Group;

use TRegx\CleanRegex\Exception\CleanRegex\Messages\Group\GroupMessage;
use TRegx\CleanRegex\Internal\Factory\GroupExceptionFactory;
use TRegx\CleanRegex\Internal\Factory\NotMatchedOptionalWorker;
use TRegx\CleanRegex\Internal\GroupNameIndexAssign;
use TRegx\CleanRegex\Internal\Match\MatchAll\MatchAllFactory;
use TRegx\CleanRegex\Internal\MatchAllResults;
use TRegx\CleanRegex\Internal\Model\Match\IRawMatchOffset;
use TRegx\CleanRegex\Internal\Model\Matches\IRawMatchesOffset;
use TRegx\CleanRegex\Internal\Subjectable;
use TRegx\CleanRegex\Match\Details\Group\MatchedGroup;
use TRegx\CleanRegex\Match\Details\Group\MatchGroup;
use TRegx\CleanRegex\Match\Details\Group\NotMatchedGroup;
use TRegx\CleanRegex\Match\Details\NotMatched;

class GroupFacade
{
    /** @var IRawMatchOffset */
    private $match;
    /** @var GroupNameIndexAssign */
    private $groupAssign;
    /** @var Subjectable */
    private $subject;
    /** @var string|int */
    private $group;
    /** @var GroupFactoryStrategy */
    private $factoryStrategy;
    /** @var MatchAllFactory */
    private $allFactory;

    public function __construct(IRawMatchOffset $match, Subjectable $subject, $group, GroupFactoryStrategy $factoryStrategy, MatchAllFactory $allFactory)
    {
        $this->match = $match;
        $this->groupAssign = new GroupNameIndexAssign($match, $allFactory);
        $this->subject = $subject;
        $this->group = $group;
        $this->factoryStrategy = $factoryStrategy;
        $this->allFactory = $allFactory;
    }

    public function createGroup(): MatchGroup
    {
        if ($this->match->isGroupMatched($this->group)) {
            [$text, $offset] = $this->match->getGroupTextAndOffset($this->group);
            return $this->createdMatched(new MatchedGroupOccurrence($text, $offset, $this->subject));
        }
        return $this->createUnmatched();
    }

    private function createdMatched(MatchedGroupOccurrence $details): MatchedGroup
    {
        return $this->factoryStrategy->createMatched($this->createGroupDetails(), $details);
    }

    private function createUnmatched(): NotMatchedGroup
    {
        return $this->factoryStrategy->createUnmatched(
            $this->createGroupDetails(),
            new GroupExceptionFactory($this->subject, $this->group),
            new NotMatchedOptionalWorker(
                new GroupMessage($this->group),
                $this->subject,
                new NotMatched($this->match, $this->subject)
            )
        );
    }

    private function createGroupDetails(): GroupDetails
    {
        [$name, $index] = $this->groupAssign->getNameAndIndex($this->group);
        return new GroupDetails($name, $index, $this->group, new MatchAllResults($this->getMatches(), $index));
    }

    private function getMatches(): IRawMatchesOffset
    {
        return $this->allFactory->getRawMatches();
    }
}

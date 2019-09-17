<?php
namespace TRegx\CleanRegex\Internal\Match\Details\Group;

use TRegx\CleanRegex\Exception\CleanRegex\Messages\Group\GroupMessage;
use TRegx\CleanRegex\Internal\Factory\GroupExceptionFactory;
use TRegx\CleanRegex\Internal\Factory\NotMatchedOptionalWorker;
use TRegx\CleanRegex\Internal\GroupNameIndexAssign;
use TRegx\CleanRegex\Internal\Match\MatchAll\MatchAllFactory;
use TRegx\CleanRegex\Internal\MatchAllResults;
use TRegx\CleanRegex\Internal\Model\IRawWithGroups;
use TRegx\CleanRegex\Internal\Model\Match\IRawMatchOffset;
use TRegx\CleanRegex\Internal\Subjectable;
use TRegx\CleanRegex\Match\Details\Group\MatchedGroup;
use TRegx\CleanRegex\Match\Details\Group\MatchGroup;
use TRegx\CleanRegex\Match\Details\Group\NotMatchedGroup;
use TRegx\CleanRegex\Match\Details\NotMatched;

class GroupFacade
{
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

    public function __construct(IRawWithGroups $groupAssignMatch, Subjectable $subject, $group, GroupFactoryStrategy $factoryStrategy, MatchAllFactory $allFactory)
    {
        $this->groupAssign = new GroupNameIndexAssign($groupAssignMatch, $allFactory);
        $this->subject = $subject;
        $this->group = $group;
        $this->factoryStrategy = $factoryStrategy;
        $this->allFactory = $allFactory;
    }

    public function createGroup(IRawMatchOffset $match): MatchGroup
    {
        if ($match->isGroupMatched($this->group)) {
            [$text, $offset] = $match->getGroupTextAndOffset($this->group);
            return $this->createdMatched($match, new MatchedGroupOccurrence($text, $offset, $this->subject));
        }
        return $this->createUnmatched($match);
    }

    private function createdMatched(IRawMatchOffset $match, MatchedGroupOccurrence $details): MatchedGroup
    {
        return $this->factoryStrategy->createMatched($match, $this->createGroupDetails(), $details);
    }

    private function createUnmatched(IRawMatchOffset $match): NotMatchedGroup
    {
        return $this->factoryStrategy->createUnmatched(
            $this->createGroupDetails(),
            new GroupExceptionFactory($this->subject, $this->group),
            new NotMatchedOptionalWorker(
                new GroupMessage($this->group),
                $this->subject,
                new NotMatched($match, $this->subject)
            )
        );
    }

    private function createGroupDetails(): GroupDetails
    {
        [$name, $index] = $this->groupAssign->getNameAndIndex($this->group);
        return new GroupDetails($name, $index, $this->group, new MatchAllResults($this->allFactory->getRawMatches(), $index));
    }
}

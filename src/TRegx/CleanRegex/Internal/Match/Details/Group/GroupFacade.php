<?php
namespace TRegx\CleanRegex\Internal\Match\Details\Group;

use TRegx\CleanRegex\Exception\CleanRegex\NotMatched\Group\GroupMessage;
use TRegx\CleanRegex\Internal\Factory\GroupExceptionFactory;
use TRegx\CleanRegex\Internal\Factory\NotMatchedOptionalWorker;
use TRegx\CleanRegex\Internal\GroupNameIndexAssign;
use TRegx\CleanRegex\Internal\MatchAllResults;
use TRegx\CleanRegex\Internal\Model\RawMatchesOffset;
use TRegx\CleanRegex\Internal\Subjectable;
use TRegx\CleanRegex\Match\Details\Group\MatchedGroup;
use TRegx\CleanRegex\Match\Details\Group\MatchGroup;
use TRegx\CleanRegex\Match\Details\Group\NotMatchedGroup;
use TRegx\CleanRegex\Match\Details\NotMatched;

class GroupFacade
{
    /** @var RawMatchesOffset */
    private $matches;
    /** @var GroupNameIndexAssign */
    private $groupAssign;
    /** @var Subjectable */
    private $subject;
    /** @var string|int */
    private $group;
    /** @var int */
    private $index;

    public function __construct(RawMatchesOffset $matches, Subjectable $subject, $group, int $index)
    {
        $this->matches = $matches;
        $this->groupAssign = new GroupNameIndexAssign($this->matches);
        $this->subject = $subject;
        $this->group = $group;
        $this->index = $index;
    }

    public function createGroup(GroupFactoryStrategy $groupFactory): MatchGroup
    {
        if ($this->matches->isGroupMatched($this->group, $this->index)) {
            list($text, $offset) = $this->matches->getGroupTextAndOffset($this->group, $this->index);
            return $this->createdMatched($groupFactory, new MatchedGroupOccurrence($text, $offset));
        }
        return $this->createUnmatched($groupFactory);
    }

    private function createdMatched(GroupFactoryStrategy $groupFactory, MatchedGroupOccurrence $details): MatchedGroup
    {
        return $groupFactory->createMatched($this->createGroupDetails(), $details);
    }

    private function createUnmatched(GroupFactoryStrategy $groupFactory): NotMatchedGroup
    {
        return $groupFactory->createUnmatched(
            $this->createGroupDetails(),
            new GroupExceptionFactory($this->subject, $this->group),
            new NotMatchedOptionalWorker(
                new GroupMessage($this->group),
                $this->subject,
                new NotMatched($this->matches, $this->subject)
            )
        );
    }

    private function createGroupDetails(): GroupDetails
    {
        list($name, $index) = $this->groupAssign->getNameAndIndex($this->group);
        return new GroupDetails($name, $index, $this->group, new MatchAllResults($this->matches, $index));
    }
}

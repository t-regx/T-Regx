<?php
namespace TRegx\CleanRegex\Internal\Factory\Group;

use TRegx\CleanRegex\Exception\CleanRegex\NotMatched\Group\GroupMessage;
use TRegx\CleanRegex\Internal\Factory\GroupExceptionFactory;
use TRegx\CleanRegex\Internal\Factory\NotMatchedOptionalWorker;
use TRegx\CleanRegex\Internal\Grouper;
use TRegx\CleanRegex\Internal\GroupNameIndexAssign;
use TRegx\CleanRegex\Match\Details\Group\MatchAll;
use TRegx\CleanRegex\Match\Details\Group\MatchedGroup;
use TRegx\CleanRegex\Match\Details\Group\MatchGroup;
use TRegx\CleanRegex\Match\Details\Group\NotMatchedGroup;
use TRegx\CleanRegex\Match\Details\NotMatched;

class GroupFacade
{
    /** @var array */
    private $matches;
    /** @var string */
    private $subject;
    /** @var string|int */
    private $group;

    /** @var Grouper */
    private $grouper;
    /** @var GroupNameIndexAssign */
    private $groupAssign;

    public function __construct(array $matches, string $subject, $group, int $index)
    {
        $this->matches = $matches;
        $this->subject = $subject;
        $this->group = $group;
        $this->grouper = new Grouper($this->matches[$this->group][$index]);
        $this->groupAssign = new GroupNameIndexAssign($this->matches);
    }

    public function createGroup(GroupFactoryStrategy $groupFactory): MatchGroup
    {
        list($text, $offset) = $this->grouper->getTextAndOffset();
        if ($offset > -1) {
            return $this->createdMatched($groupFactory, new MatchedGroupDetails($text, $offset));
        }
        return $this->createUnmatched($groupFactory);
    }

    private function createdMatched(GroupFactoryStrategy $groupFactory, MatchedGroupDetails $details): MatchedGroup
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
        return new GroupDetails($name, $index, $this->group, new MatchAll($this->matches, $index));
    }
}

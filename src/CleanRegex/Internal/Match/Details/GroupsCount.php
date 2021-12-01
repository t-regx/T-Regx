<?php
namespace TRegx\CleanRegex\Internal\Match\Details;

use TRegx\CleanRegex\Exception\InternalCleanRegexException;
use TRegx\CleanRegex\Internal\Model\GroupAware;

class GroupsCount
{
    /** @var GroupAware */
    private $groupAware;

    public function __construct(GroupAware $groupAware)
    {
        $this->groupAware = $groupAware;
    }

    public function groupsCount(): int
    {
        return $this->groups($this->wholeMatchAndIndexedGroups());
    }

    private function groups(int $count): int
    {
        if ($count === 0) {
            // @codeCoverageIgnoreStart
            throw new InternalCleanRegexException();
            // @codeCoverageIgnoreEnd
        }
        return $count - 1;
    }

    private function wholeMatchAndIndexedGroups(): int
    {
        return \count(\array_filter($this->groupAware->getGroupKeys(), '\is_int'));
    }
}

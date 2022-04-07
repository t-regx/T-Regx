<?php
namespace TRegx\CleanRegex\Internal\Match\Details;

use TRegx\CleanRegex\Exception\InternalCleanRegexException;
use TRegx\CleanRegex\Internal\Model\GroupKeys;

class GroupsCount
{
    /** @var GroupKeys */
    private $groupKeys;

    public function __construct(GroupKeys $groupKeys)
    {
        $this->groupKeys = $groupKeys;
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
        return \count(\array_filter($this->groupKeys->getGroupKeys(), '\is_int'));
    }
}

<?php
namespace TRegx\CleanRegex\Internal\Match\Details\Group;

use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\Match\Details\Group\Handle\GroupHandle;
use TRegx\CleanRegex\Internal\Model\Match\UsedForGroup;
use TRegx\CleanRegex\Internal\Subject;

class GroupEntryFactory
{
    /** @var Subject */
    private $subject;
    /** @var GroupHandle */
    private $groupHandle;

    public function __construct(Subject $subject, GroupHandle $groupHandle)
    {
        $this->subject = $subject;
        $this->groupHandle = $groupHandle;
    }

    public function groupEntry(GroupKey $group, UsedForGroup $forGroup): GroupEntry
    {
        if ($forGroup->isGroupMatched($this->groupHandle->groupHandle($group))) {
            [$text, $offset] = $forGroup->getGroupTextAndOffset($this->groupHandle->groupHandle($group));
            return new GroupEntry($text, $offset, $this->subject);
        }
        throw new UnmatchedGroupException();
    }
}

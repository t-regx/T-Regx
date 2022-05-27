<?php
namespace TRegx\CleanRegex\Internal\Match\Details\Group;

use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\GroupKey\Signatures;
use TRegx\CleanRegex\Internal\Match\Details\Group\Handle\GroupHandle;
use TRegx\CleanRegex\Internal\Pcre\Legacy\MatchAllFactory;
use TRegx\CleanRegex\Internal\Pcre\Legacy\RawMatchesOffset;
use TRegx\CleanRegex\Internal\Pcre\Legacy\RawMatchesToMatchAdapter;
use TRegx\CleanRegex\Internal\Subject;

class GroupsFacade
{
    /** @var GroupHandle */
    private $groupHandle;
    /** @var GroupFacade */
    private $single;

    public function __construct(Subject $subject, GroupFactoryStrategy $factoryStrategy, MatchAllFactory $allFactory, GroupHandle $groupHandle, Signatures $signatures)
    {
        $this->groupHandle = $groupHandle;
        $this->single = new GroupFacade($subject, $factoryStrategy, $allFactory, $groupHandle, $signatures);
    }

    public function createGroups(GroupKey $groupKey, RawMatchesOffset $matches): array
    {
        $groupIndexes = \array_keys($matches->getGroupTextAndOffsetAll($this->groupHandle->groupHandle($groupKey)));
        $result = [];
        foreach ($groupIndexes as $index) {
            $match = new RawMatchesToMatchAdapter($matches, $index);
            $result[$index] = $this->single->createGroup($groupKey, $match, $match);
        }
        return $result;
    }
}

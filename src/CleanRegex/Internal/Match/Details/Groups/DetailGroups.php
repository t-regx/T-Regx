<?php
namespace TRegx\CleanRegex\Internal\Match\Details\Groups;

use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\GroupKey\Signatures;
use TRegx\CleanRegex\Internal\Match\Details\Group\GroupFacade;
use TRegx\CleanRegex\Internal\Match\Details\Group\Handle\GroupHandle;
use TRegx\CleanRegex\Internal\Match\Details\Group\MatchGroupFactoryStrategy;
use TRegx\CleanRegex\Internal\Model\Entry;
use TRegx\CleanRegex\Internal\Model\GroupKeys;
use TRegx\CleanRegex\Internal\Pcre\Legacy\MatchAllFactory;
use TRegx\CleanRegex\Internal\Pcre\Legacy\UsedForGroup;
use TRegx\CleanRegex\Internal\Subject;

class DetailGroups
{
    /** @var GroupKeys */
    private $groupKeys;
    /** @var GroupFacade */
    private $facade;

    public function __construct(Subject $subject, Signatures $signatures, MatchAllFactory $allFactory, GroupKeys $groupKeys)
    {
        $this->groupKeys = $groupKeys;
        $this->facade = new GroupFacade($subject, new MatchGroupFactoryStrategy(), $allFactory, new GroupHandle($signatures), $signatures);
    }

    public function groups(GroupArrayKey $arrayKey, UsedForGroup $forGroup, Entry $entry): array
    {
        $groups = [];
        foreach ($this->groupKeys->getGroupKeys() as $groupKey) {
            if ($arrayKey->applies($groupKey)) {
                $groups[$arrayKey->key($groupKey)] = $this->facade->createGroup(GroupKey::of($groupKey), $forGroup, $entry);
            }
        }
        return $groups;
    }
}

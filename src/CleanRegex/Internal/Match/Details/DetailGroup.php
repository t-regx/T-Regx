<?php
namespace TRegx\CleanRegex\Internal\Match\Details;

use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\CleanRegex\Exception\NonexistentGroupException;
use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\GroupKey\Signatures;
use TRegx\CleanRegex\Internal\Match\Details\Group\GroupFacade;
use TRegx\CleanRegex\Internal\Match\Details\Group\GroupFactoryStrategy;
use TRegx\CleanRegex\Internal\Match\Details\Group\Handle\GroupHandle;
use TRegx\CleanRegex\Internal\Model\Entry;
use TRegx\CleanRegex\Internal\Model\GroupAware;
use TRegx\CleanRegex\Internal\Pcre\Legacy\MatchAllFactory;
use TRegx\CleanRegex\Internal\Pcre\Legacy\UsedForGroup;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\CleanRegex\Match\Details\Group\Group;

class DetailGroup
{
    /** @var GroupAware */
    private $groupAware;
    /** @var Entry */
    private $entry;
    /** @var UsedForGroup */
    private $usedForGroup;
    /** @var GroupHandle */
    private $groupHandle;
    /** @var GroupFacade */
    private $groupFacade;

    public function __construct(
        GroupAware           $groupAware,
        Entry                $matchEntry,
        UsedForGroup         $usedForGroup,
        Signatures           $signatures,
        GroupFactoryStrategy $strategy,
        MatchAllFactory      $allFactory,
        Subject              $subject)
    {
        $this->groupAware = $groupAware;
        $this->entry = $matchEntry;
        $this->usedForGroup = $usedForGroup;
        $this->groupHandle = new GroupHandle($signatures);
        $this->groupFacade = new GroupFacade($subject, $strategy, $allFactory, $this->groupHandle, $signatures);
    }

    public function exists(GroupKey $group): bool
    {
        return $this->groupAware->hasGroup($group);
    }

    public function matched(GroupKey $groupKey): bool
    {
        if ($this->exists($groupKey)) {
            return $this->usedForGroup->isGroupMatched($this->groupHandle->groupHandle($groupKey));
        }
        throw new NonexistentGroupException($groupKey);
    }

    public function group(GroupKey $group): Group
    {
        if ($this->exists($group)) {
            return $this->groupFacade->createGroup($group, $this->usedForGroup, $this->entry);
        }
        throw new NonexistentGroupException($group);
    }

    public function text(GroupKey $group): string
    {
        if ($this->exists($group)) {
            return $this->groupText($group);
        }
        throw new NonexistentGroupException($group);
    }

    private function groupText(GroupKey $group): string
    {
        $handle = $this->groupHandle->groupHandle($group);
        if ($this->usedForGroup->isGroupMatched($handle)) {
            [$text] = $this->usedForGroup->getGroupTextAndOffset($handle);
            return $text;
        }
        throw GroupNotMatchedException::forGet($group);
    }
}

<?php
namespace TRegx\CleanRegex\Match\Details\Groups;

use TRegx\CleanRegex\Internal\GroupNames;
use TRegx\CleanRegex\Internal\Model\GroupAware;
use TRegx\CleanRegex\Internal\Model\Match\UsedInCompositeGroups;
use TRegx\CleanRegex\Internal\Subjectable;

class IndexedGroups extends AbstractMatchGroups
{
    /** @var GroupAware */
    private $groupAware;

    public function __construct(GroupAware $groupAware, UsedInCompositeGroups $match, Subjectable $subjectable)
    {
        parent::__construct($match, $subjectable);
        $this->groupAware = $groupAware;
    }

    protected function filterGroupKey($nameOrIndex): bool
    {
        return \is_int($nameOrIndex);
    }

    public function names(): array
    {
        return (new GroupNames($this->groupAware))->groupNames();
    }

    public function count(): int
    {
        return \max(0, \count(\array_filter($this->groupAware->getGroupKeys(), '\is_int')) - 1);
    }
}

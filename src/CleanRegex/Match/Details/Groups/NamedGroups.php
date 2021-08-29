<?php
namespace TRegx\CleanRegex\Match\Details\Groups;

use TRegx\CleanRegex\Internal\Model\GroupAware;
use TRegx\CleanRegex\Internal\Model\Match\UsedInCompositeGroups;
use TRegx\CleanRegex\Internal\Subject;

class NamedGroups extends AbstractMatchGroups
{
    /** @var GroupAware */
    private $groupAware;

    public function __construct(GroupAware $groupAware, UsedInCompositeGroups $match, Subject $subject)
    {
        parent::__construct($match, $subject);
        $this->groupAware = $groupAware;
    }

    protected function filterGroupKey($nameOrIndex): bool
    {
        return \is_string($nameOrIndex);
    }

    public function names(): array
    {
        return \array_values(\array_filter($this->groupAware->getGroupKeys(), '\is_string'));
    }

    public function count(): int
    {
        return \count(\array_filter($this->groupAware->getGroupKeys(), '\is_string'));
    }
}

<?php
namespace TRegx\CleanRegex\Match\Details\Groups;

use TRegx\CleanRegex\Internal\Model\GroupAware;
use TRegx\CleanRegex\Internal\Model\Match\GroupEntries;
use TRegx\CleanRegex\Internal\Subject;

class NamedGroups extends AbstractMatchGroups
{
    /** @var GroupAware */
    private $groupAware;

    public function __construct(GroupAware $groupAware, GroupEntries $entries, Subject $subject)
    {
        parent::__construct($entries, $subject);
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

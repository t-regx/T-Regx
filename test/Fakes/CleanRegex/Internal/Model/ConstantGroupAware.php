<?php
namespace Test\Fakes\CleanRegex\Internal\Model;

use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\Model\GroupAware;

class ConstantGroupAware implements GroupAware
{
    /** @var string[] */
    private $groupKeys;

    public function __construct(array $groupKeys)
    {
        $this->groupKeys = $groupKeys;
    }

    public function hasGroup(GroupKey $group): bool
    {
        return \in_array($group->nameOrIndex(), $this->groupKeys, true);
    }

    public function getGroupKeys(): array
    {
        return $this->groupKeys;
    }
}

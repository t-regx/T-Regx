<?php
namespace Test\Fakes\CleanRegex\Internal\Model;

use TRegx\CleanRegex\Internal\Model\GroupAware;

class GroupKeys implements GroupAware
{
    /** @var array */
    private $groupKeys;

    public function __construct(array $groupKeys)
    {
        $this->groupKeys = $groupKeys;
    }

    public function hasGroup($nameOrIndex): bool
    {
        return \in_array($nameOrIndex, $this->groupKeys);
    }

    public function getGroupKeys(): array
    {
        return $this->groupKeys;
    }
}

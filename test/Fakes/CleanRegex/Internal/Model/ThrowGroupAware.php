<?php
namespace Test\Fakes\CleanRegex\Internal\Model;

use AssertionError;
use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\Model\GroupAware;

class ThrowGroupAware implements GroupAware
{
    public function hasGroup(GroupKey $group): bool
    {
        throw new AssertionError("Failed to assert that ThrowGroupAware wasn't used");
    }

    public function getGroupKeys(): array
    {
        throw new AssertionError("Failed to assert that ThrowGroupAware wasn't used");
    }
}

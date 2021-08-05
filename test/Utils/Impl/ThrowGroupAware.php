<?php
namespace Test\Utils\Impl;

use AssertionError;
use TRegx\CleanRegex\Internal\Model\GroupAware;

class ThrowGroupAware implements GroupAware
{
    public function hasGroup($nameOrIndex): bool
    {
        throw new AssertionError("Failed to assert that GroupAware wasn't used");
    }

    public function getGroupKeys(): array
    {
        throw new AssertionError("Failed to assert that GroupAware wasn't used");
    }
}

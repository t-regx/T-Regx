<?php
namespace Test\Utils\Impl;

use AssertionError;
use TRegx\CleanRegex\Internal\Model\Match\UsedInCompositeGroups;

class ThrowRenameMe implements UsedInCompositeGroups
{
    public function getGroupsTexts(): array
    {
        throw new AssertionError("Failed to assert that ThrowRenameMe wasn't used");
    }

    public function getGroupsOffsets(): array
    {
        throw new AssertionError("Failed to assert that ThrowRenameMe wasn't used");
    }
}

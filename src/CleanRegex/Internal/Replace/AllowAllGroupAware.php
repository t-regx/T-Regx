<?php
namespace TRegx\CleanRegex\Internal\Replace;

use TRegx\CleanRegex\Exception\InternalCleanRegexException;
use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\Model\GroupAware;

class AllowAllGroupAware implements GroupAware
{
    public function getGroupKeys(): array
    {
        throw new InternalCleanRegexException();
    }

    public function hasGroup(GroupKey $group): bool
    {
        return true;
    }
}

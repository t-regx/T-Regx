<?php
namespace Test\Fakes\CleanRegex\Internal\Model;

use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\Model\GroupHasAware;

class IgnoreAware implements GroupHasAware
{
    public function hasGroup(GroupKey $group): bool
    {
        return true;
    }
}

<?php
namespace TRegx\CleanRegex\Internal\Model;

use TRegx\CleanRegex\Internal\GroupKey\GroupKey;

interface GroupHasAware
{
    public function hasGroup(GroupKey $group): bool;
}

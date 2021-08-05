<?php
namespace TRegx\CleanRegex\Internal\Match\Details\Group\Handle;

use TRegx\CleanRegex\Internal\GroupKey\GroupKey;

interface GroupHandle
{
    public function groupHandle(GroupKey $groupKey);
}

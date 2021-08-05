<?php
namespace TRegx\CleanRegex\Internal\Match\Details\Group\Handle;

use TRegx\CleanRegex\Internal\GroupKey\GroupKey;

class RuntimeNamedGroup implements GroupHandle
{
    public function groupHandle(GroupKey $groupKey): string
    {
        return $groupKey->nameOrIndex();
    }
}

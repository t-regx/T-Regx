<?php
namespace TRegx\CleanRegex\Match\Details\Group;

class ReplaceNotMatchedGroup extends NotMatchedGroup implements ReplaceDetailGroup, ReplaceMatchGroup
{
    public function modifiedOffset(): int
    {
        throw $this->groupNotMatched('modifiedOffset');
    }
}

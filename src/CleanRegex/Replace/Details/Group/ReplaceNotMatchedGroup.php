<?php
namespace TRegx\CleanRegex\Replace\Details\Group;

use TRegx\CleanRegex\Match\Details\Group\NotMatchedGroup;

class ReplaceNotMatchedGroup extends NotMatchedGroup implements ReplaceGroup
{
    /**
     * @deprecated
     */
    public function modifiedSubject(): string
    {
        throw $this->groupNotMatched('modifiedSubject');
    }

    /**
     * @deprecated
     */
    public function modifiedOffset(): int
    {
        throw $this->groupNotMatched('modifiedOffset');
    }

    /**
     * @deprecated
     */
    public function byteModifiedOffset(): int
    {
        throw $this->groupNotMatched('byteModifiedOffset');
    }
}

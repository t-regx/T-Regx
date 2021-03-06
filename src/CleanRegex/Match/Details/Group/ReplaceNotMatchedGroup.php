<?php
namespace TRegx\CleanRegex\Match\Details\Group;

class ReplaceNotMatchedGroup extends NotMatchedGroup implements ReplaceGroup
{
    public function modifiedSubject(): string
    {
        throw $this->groupNotMatched('modifiedSubject');
    }

    public function modifiedOffset(): int
    {
        throw $this->groupNotMatched('modifiedOffset');
    }

    public function byteModifiedOffset(): int
    {
        throw $this->groupNotMatched('byteModifiedOffset');
    }
}

<?php
namespace TRegx\CleanRegex\Match\Details\Group;

interface ReplaceGroup extends Group
{
    public function modifiedSubject(): string;

    public function modifiedOffset(): int;

    public function byteModifiedOffset(): int;
}

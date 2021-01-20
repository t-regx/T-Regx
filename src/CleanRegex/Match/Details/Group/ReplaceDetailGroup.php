<?php
namespace TRegx\CleanRegex\Match\Details\Group;

interface ReplaceDetailGroup extends DetailGroup
{
    public function modifiedOffset(): int;

    public function byteModifiedOffset(): int;
}

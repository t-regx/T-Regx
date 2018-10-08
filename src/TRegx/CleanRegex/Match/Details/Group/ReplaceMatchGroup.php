<?php
namespace TRegx\CleanRegex\Match\Details\Group;

interface ReplaceMatchGroup extends MatchGroup
{
    public function modifiedOffset(): int;
}

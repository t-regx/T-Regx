<?php
namespace TRegx\CleanRegex\Match\Details\Group;

interface DuplicateNamedGroup extends MatchGroupDetails
{
    public function name(): string;
}

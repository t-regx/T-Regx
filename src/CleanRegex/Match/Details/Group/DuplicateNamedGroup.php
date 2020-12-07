<?php
namespace TRegx\CleanRegex\Match\Details\Group;

interface DuplicateNamedGroup extends BaseDetailGroup
{
    public function name(): string;
}

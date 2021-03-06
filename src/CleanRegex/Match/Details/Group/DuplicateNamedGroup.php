<?php
namespace TRegx\CleanRegex\Match\Details\Group;

interface DuplicateNamedGroup extends CapturingGroup
{
    public function name(): string;
}

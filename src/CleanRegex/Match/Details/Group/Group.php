<?php
namespace TRegx\CleanRegex\Match\Details\Group;

interface Group extends CapturingGroup
{
    public function index(): int;
}

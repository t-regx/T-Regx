<?php
namespace TRegx\CleanRegex\Match\Details\Group;

use TRegx\CleanRegex\Match\Details\Intable;

interface Group extends CapturingGroup, Intable
{
    public function index(): int;
}

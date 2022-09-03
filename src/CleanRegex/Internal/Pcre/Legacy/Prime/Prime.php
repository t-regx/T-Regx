<?php
namespace TRegx\CleanRegex\Internal\Pcre\Legacy\Prime;

use TRegx\CleanRegex\Internal\Pcre\Legacy\UsedForGroup;

interface Prime
{
    public function firstUsedForGroup(): UsedForGroup;
}

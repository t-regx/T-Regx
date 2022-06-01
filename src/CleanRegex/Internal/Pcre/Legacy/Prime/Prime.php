<?php
namespace TRegx\CleanRegex\Internal\Pcre\Legacy\Prime;

use TRegx\CleanRegex\Internal\Model\Entry;
use TRegx\CleanRegex\Internal\Pcre\Legacy\UsedForGroup;

interface Prime
{
    public function firstUsedForGroup(): UsedForGroup;

    public function firstEntry(): Entry;
}

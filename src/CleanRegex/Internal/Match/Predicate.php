<?php
namespace TRegx\CleanRegex\Internal\Match;

use TRegx\CleanRegex\Match\Details\Detail;

interface Predicate
{
    public function test(Detail $detail): bool;
}

<?php
namespace TRegx\CleanRegex\Internal\Match\MatchAll;

use TRegx\CleanRegex\Internal\Model\IRawMatches;

interface MatchAllFactory
{
    public function getRawMatches(): IRawMatches;
}

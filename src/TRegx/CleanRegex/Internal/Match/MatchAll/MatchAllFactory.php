<?php
namespace TRegx\CleanRegex\Internal\Match\MatchAll;

use TRegx\CleanRegex\Internal\Model\IRawMatchesOffset;

interface MatchAllFactory
{
    public function getRawMatches(): IRawMatchesOffset;
}

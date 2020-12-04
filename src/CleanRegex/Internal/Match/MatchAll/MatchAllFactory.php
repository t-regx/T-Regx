<?php
namespace TRegx\CleanRegex\Internal\Match\MatchAll;

use TRegx\CleanRegex\Internal\Model\Matches\RawMatchesOffset;

interface MatchAllFactory
{
    public function getRawMatches(): RawMatchesOffset;
}

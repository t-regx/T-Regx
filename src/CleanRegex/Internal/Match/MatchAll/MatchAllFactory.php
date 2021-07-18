<?php
namespace TRegx\CleanRegex\Internal\Match\MatchAll;

use TRegx\CleanRegex\Internal\Model\Match\RawMatchesOffset;

interface MatchAllFactory
{
    public function getRawMatches(): RawMatchesOffset;
}

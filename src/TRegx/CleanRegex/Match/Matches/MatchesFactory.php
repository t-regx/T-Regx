<?php
namespace TRegx\CleanRegex\Match\Matches;

use TRegx\CleanRegex\Internal\Model\RawMatchesOffset;

interface MatchesFactory
{
    public function getMatches(): RawMatchesOffset;
}

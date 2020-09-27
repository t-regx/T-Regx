<?php
namespace TRegx\CleanRegex\Replace\NonReplaced;

use TRegx\CleanRegex\Match\Details\Match;

interface MatchRs
{
    public function substituteGroup(Match $match): ?string;
}

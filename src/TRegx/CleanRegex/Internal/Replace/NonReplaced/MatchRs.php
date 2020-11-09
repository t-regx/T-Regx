<?php
namespace TRegx\CleanRegex\Internal\Replace\NonReplaced;

use TRegx\CleanRegex\Match\Details\Detail;

interface MatchRs
{
    public function substituteGroup(Detail $match): ?string;
}

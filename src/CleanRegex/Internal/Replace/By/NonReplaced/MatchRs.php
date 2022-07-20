<?php
namespace TRegx\CleanRegex\Internal\Replace\By\NonReplaced;

use TRegx\CleanRegex\Match\Detail;

interface MatchRs
{
    public function substituteGroup(Detail $detail): ?string;
}

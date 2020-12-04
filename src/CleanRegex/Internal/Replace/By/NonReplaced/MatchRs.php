<?php
namespace TRegx\CleanRegex\Internal\Replace\By\NonReplaced;

use TRegx\CleanRegex\Match\Details\Detail;

interface MatchRs
{
    public function substituteGroup(Detail $detail): ?string;
}

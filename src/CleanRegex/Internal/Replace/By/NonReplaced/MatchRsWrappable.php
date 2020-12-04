<?php
namespace TRegx\CleanRegex\Internal\Replace\By\NonReplaced;

use TRegx\CleanRegex\Internal\Replace\Wrappable;
use TRegx\CleanRegex\Match\Details\Detail;

class MatchRsWrappable implements Wrappable
{
    /** @var MatchRs */
    private $matchRs;

    public function __construct(MatchRs $matchRs)
    {
        $this->matchRs = $matchRs;
    }

    public function apply(Detail $detail): ?string
    {
        return $this->matchRs->substituteGroup($detail);
    }
}

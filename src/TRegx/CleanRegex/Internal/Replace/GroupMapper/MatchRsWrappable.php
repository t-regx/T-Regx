<?php
namespace TRegx\CleanRegex\Internal\Replace\GroupMapper;

use TRegx\CleanRegex\Internal\Replace\NonReplaced\MatchRs;
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

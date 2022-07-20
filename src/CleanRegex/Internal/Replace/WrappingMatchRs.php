<?php
namespace TRegx\CleanRegex\Internal\Replace;

use TRegx\CleanRegex\Internal\Replace\By\NonReplaced\MatchRs;
use TRegx\CleanRegex\Internal\Replace\By\NonReplaced\MatchRsWrappable;
use TRegx\CleanRegex\Match\Detail;

class WrappingMatchRs implements MatchRs
{
    /** @var MatchRs */
    private $matchRs;
    /** @var Wrapper */
    private $mapperWrapper;

    public function __construct(MatchRs $matchRs, Wrapper $mapperWrapper)
    {
        $this->matchRs = $matchRs;
        $this->mapperWrapper = $mapperWrapper;
    }

    public function substituteGroup(Detail $detail): ?string
    {
        return $this->mapperWrapper->wrap(new MatchRsWrappable($this->matchRs), $detail);
    }
}

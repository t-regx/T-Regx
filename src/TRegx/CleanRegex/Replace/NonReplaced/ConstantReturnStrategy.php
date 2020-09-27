<?php
namespace TRegx\CleanRegex\Replace\NonReplaced;

use TRegx\CleanRegex\Match\Details\Match;

class ConstantReturnStrategy implements SubjectRs, MatchRs
{
    /** @var string */
    private $constant;

    public function __construct(string $constant)
    {
        $this->constant = $constant;
    }

    public function substitute(string $subject): string
    {
        return $this->constant;
    }

    public function substituteGroup(Match $match): string
    {
        return $this->constant;
    }
}

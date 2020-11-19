<?php
namespace TRegx\CleanRegex\Internal\Replace\NonReplaced;

use TRegx\CleanRegex\Match\Details\Detail;

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

    public function substituteGroup(Detail $detail): string
    {
        return $this->constant;
    }
}

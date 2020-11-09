<?php
namespace TRegx\CleanRegex\Replace\By;

use TRegx\CleanRegex\Exception\InternalCleanRegexException;
use TRegx\CleanRegex\Internal\Replace\NonReplaced\MatchRs;
use TRegx\CleanRegex\Match\Details\Detail;

class ThrowMatchRs implements MatchRs
{
    public function substituteGroup(Detail $match): string
    {
        // @codeCoverageIgnoreStart
        throw new InternalCleanRegexException();
        // @codeCoverageIgnoreEnd
    }
}

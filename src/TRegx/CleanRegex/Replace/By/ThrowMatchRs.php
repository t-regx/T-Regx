<?php
namespace TRegx\CleanRegex\Replace\By;

use TRegx\CleanRegex\Exception\InternalCleanRegexException;
use TRegx\CleanRegex\Match\Details\Match;
use TRegx\CleanRegex\Replace\NonReplaced\MatchRs;

class ThrowMatchRs implements MatchRs
{
    public function substituteGroup(Match $match): string
    {
        // @codeCoverageIgnoreStart
        throw new InternalCleanRegexException();
        // @codeCoverageIgnoreEnd
    }
}

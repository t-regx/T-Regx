<?php
namespace TRegx\CleanRegex\Internal\Replace\NonReplaced;

use TRegx\CleanRegex\Exception\InternalCleanRegexException;
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

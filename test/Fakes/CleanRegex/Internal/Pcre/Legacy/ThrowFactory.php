<?php
namespace Test\Fakes\CleanRegex\Internal\Pcre\Legacy;

use AssertionError;
use TRegx\CleanRegex\Internal\Pcre\Legacy\MatchAllFactory;
use TRegx\CleanRegex\Internal\Pcre\Legacy\RawMatchesOffset;

class ThrowFactory implements MatchAllFactory
{
    public function getRawMatches(): RawMatchesOffset
    {
        throw new AssertionError("Failed to assert that MatchAllFactory wasn't used");
    }
}

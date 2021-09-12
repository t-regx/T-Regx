<?php
namespace Test\Fakes\CleanRegex\Internal\Match\MatchAll;

use AssertionError;
use TRegx\CleanRegex\Internal\Match\MatchAll\MatchAllFactory;
use TRegx\CleanRegex\Internal\Model\Match\RawMatchesOffset;

class ThrowFactory implements MatchAllFactory
{
    public function getRawMatches(): RawMatchesOffset
    {
        throw new AssertionError("Failed to assert that MatchAllFactory wasn't used");
    }
}

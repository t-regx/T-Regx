<?php
namespace Test\Utils\Impl;

use AssertionError;
use TRegx\CleanRegex\Internal\Match\MatchAll\MatchAllFactory;
use TRegx\CleanRegex\Internal\Model\Match\RawMatchesOffset;

class ThrowFactory implements MatchAllFactory
{
    public function getRawMatches(): RawMatchesOffset
    {
        throw new AssertionError("Failed to assert that all-factory wasn't used");
    }
}

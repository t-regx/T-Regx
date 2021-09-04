<?php
namespace Test\Utils\Impl;

use AssertionError;
use TRegx\CleanRegex\Internal\Model\Match\Entry;

class ThrowEntry implements Entry
{
    public function text(): string
    {
        throw new AssertionError("Failed to assert that MatchEntry wasn't used");
    }

    public function byteOffset(): int
    {
        throw new AssertionError("Failed to assert that MatchEntry wasn't used");
    }
}

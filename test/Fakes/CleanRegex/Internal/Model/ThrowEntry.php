<?php
namespace Test\Fakes\CleanRegex\Internal\Model;

use AssertionError;
use TRegx\CleanRegex\Internal\Model\Entry;

class ThrowEntry implements Entry
{
    public function text(): string
    {
        throw new AssertionError("Failed to assert that Entry wasn't used");
    }

    public function byteOffset(): int
    {
        throw new AssertionError("Failed to assert that Entry wasn't used");
    }
}

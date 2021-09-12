<?php
namespace Test\Utils\Impl;

use AssertionError;
use TRegx\CleanRegex\Internal\Condition;

class ThrowCondition implements Condition
{
    public function suitable(string $candidate): bool
    {
        throw new AssertionError("Failed to assert that Delimiters weren't used");
    }
}

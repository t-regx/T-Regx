<?php
namespace SafeRegex\Errors\Errors;

use CleanRegex\Exception\CleanRegex\InternalCleanRegexException;
use SafeRegex\Errors\HostError;
use SafeRegex\Exception\SafeRegexException;

class EmptyHostError implements HostError
{
    public function occurred(): bool
    {
        return false;
    }

    public function clear(): void
    {
    }

    public function getSafeRegexpException(string $methodName): SafeRegexException
    {
        throw new InternalCleanRegexException();
    }
}

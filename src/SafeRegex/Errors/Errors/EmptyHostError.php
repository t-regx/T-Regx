<?php
namespace SafeRegex\Errors\Errors;

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

    public function getSafeRegexpException(string $methodName): ?SafeRegexException
    {
        return null;
    }
}

<?php
namespace TRegx\SafeRegex\Errors\Errors;

use TRegx\CleanRegex\Exception\InternalCleanRegexException;
use TRegx\SafeRegex\Errors\HostError;
use TRegx\SafeRegex\Exception\PregException;

class EmptyHostError implements HostError
{
    public function occurred(): bool
    {
        return false;
    }

    public function clear(): void
    {
    }

    public function getSafeRegexpException(string $methodName): PregException
    {
        throw new InternalCleanRegexException();
    }
}

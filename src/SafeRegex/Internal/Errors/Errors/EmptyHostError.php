<?php
namespace TRegx\SafeRegex\Internal\Errors\Errors;

use TRegx\CleanRegex\Exception\InternalCleanRegexException;
use TRegx\SafeRegex\Exception\PregException;
use TRegx\SafeRegex\Internal\Errors\HostError;

class EmptyHostError implements HostError
{
    public function occurred(): bool
    {
        return false;
    }

    public function clear(): void
    {
    }

    public function getSafeRegexpException(string $methodName, $pattern): PregException
    {
        // @codeCoverageIgnoreStart
        throw new InternalCleanRegexException();
        // @codeCoverageIgnoreEnd
    }
}

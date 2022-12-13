<?php
namespace TRegx\SafeRegex\Internal\Errors;

use TRegx\CleanRegex\Exception\InternalCleanRegexException;
use TRegx\SafeRegex\Exception\PregException;

class NoError implements CompileError
{
    public function occurred(): bool
    {
        return false;
    }

    /**
     * @param string $methodName
     * @param string|string[] $pattern
     * @return PregException
     */
    public function getSafeRegexpException(string $methodName, $pattern): PregException
    {
        // @codeCoverageIgnoreStart
        throw new InternalCleanRegexException();
        // @codeCoverageIgnoreEnd
    }
}

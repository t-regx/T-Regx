<?php
namespace TRegx\SafeRegex\Internal\Errors\Errors;

use TRegx\CleanRegex\Exception\InternalCleanRegexException;
use TRegx\SafeRegex\Exception\PregException;

class IrrelevantCompileError implements CompileError
{
    public function occurred(): bool
    {
        // It really occurred, but let T-Regx think nothing happened.
        // We don't care about that error anyway.
        return false;
    }

    public function clear(): void
    {
        // @codeCoverageIgnoreStart
        throw new InternalCleanRegexException();
        // @codeCoverageIgnoreEnd
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

<?php
namespace TRegx\SafeRegex\Errors\Errors;

use TRegx\CleanRegex\Exception\InternalCleanRegexException;
use TRegx\SafeRegex\Exception\PregException;

class IrrelevantCompileError implements CompileError
{
    public function occurred(): bool
    {
        return false; // It really occurred, but let T-Regx think nothing happened
    }

    public function clear(): void
    {
        // @codeCoverageIgnoreStart
        throw new InternalCleanRegexException();
        // @codeCoverageIgnoreEnd
    }

    public function getSafeRegexpException(string $methodName): PregException
    {
        // @codeCoverageIgnoreStart
        throw new InternalCleanRegexException();
        // @codeCoverageIgnoreEnd
    }
}

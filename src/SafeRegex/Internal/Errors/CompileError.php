<?php
namespace TRegx\SafeRegex\Internal\Errors;

use TRegx\SafeRegex\Exception\PregException;

interface CompileError
{
    public function occurred(): bool;

    /**
     * @param string $methodName
     * @param string|string[] $pattern
     * @return PregException
     */
    public function getSafeRegexpException(string $methodName, $pattern): PregException;
}

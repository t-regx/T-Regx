<?php
namespace TRegx\SafeRegex\Internal\Errors;

use TRegx\SafeRegex\Exception\PregException;

interface HostError
{
    public function occurred(): bool;

    public function clear(): void;

    /**
     * @param string $methodName
     * @param string|string[] $pattern
     * @return PregException
     */
    public function getSafeRegexpException(string $methodName, $pattern): PregException;
}

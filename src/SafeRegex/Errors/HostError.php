<?php
namespace SafeRegex\Errors;

use SafeRegex\Exception\SafeRegexException;

interface HostError
{
    public function occurred(): bool;

    public function clear(): void;

    public function getSafeRegexpException(string $methodName): SafeRegexException;
}

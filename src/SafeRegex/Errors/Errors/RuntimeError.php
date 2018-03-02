<?php
namespace SafeRegex\Errors\Errors;

use SafeRegex\Errors\HostError;
use SafeRegex\Exception\RuntimeSafeRegexException;
use SafeRegex\Exception\SafeRegexException;

class RuntimeError implements HostError
{
    /** @var int */
    private $pregError;

    public function __construct(int $pregError)
    {
        $this->pregError = $pregError;
    }

    public function occurred(): bool
    {
        return $this->pregError !== PREG_NO_ERROR;
    }

    public function clear(): void
    {
        preg_match('//', '');
    }

    public static function getLast(): RuntimeError
    {
        return new RuntimeError(preg_last_error());
    }

    public function getSafeRegexpException(string $methodName): SafeRegexException
    {
        return new RuntimeSafeRegexException($methodName, $this->pregError);
    }
}

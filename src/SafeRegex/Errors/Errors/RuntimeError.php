<?php
namespace SafeRegex\Errors\Errors;

use SafeRegex\Constants\PregConstants;
use SafeRegex\Errors\HostError;
use SafeRegex\Exception\Factory\RuntimeSafeRegexExceptionFactory;
use SafeRegex\Exception\SafeRegexException;

class RuntimeError implements HostError
{
    /** @var int */
    private $pregError;

    /** @var string */
    private $pregConstant;

    public function __construct(int $pregError)
    {
        $this->pregError = $pregError;
        $this->pregConstant = (new PregConstants())->getConstant($pregError);
    }

    public function occurred(): bool
    {
        return $this->pregError !== PREG_NO_ERROR;
    }

    public function clear(): void
    {
        preg_match('//', '');
    }

    public function getSafeRegexpException(string $methodName): SafeRegexException
    {
        return (new RuntimeSafeRegexExceptionFactory($methodName, $this->pregError))->create();
    }

    public static function getLast(): RuntimeError
    {
        return new RuntimeError(preg_last_error());
    }
}

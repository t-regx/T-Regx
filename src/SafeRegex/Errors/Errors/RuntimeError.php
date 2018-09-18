<?php
namespace SafeRegex\Errors\Errors;

use CleanRegex\Exception\CleanRegex\InternalCleanRegexException;
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
        if ($this->occurred()) {
            return (new RuntimeSafeRegexExceptionFactory($methodName, $this->pregError))->create();
        }
        throw new InternalCleanRegexException();
    }

    public static function getLast(): RuntimeError
    {
        return new RuntimeError(preg_last_error());
    }
}

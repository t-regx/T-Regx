<?php
namespace TRegx\SafeRegex\Errors\Errors;

use TRegx\CleanRegex\Exception\CleanRegex\InternalCleanRegexException;
use TRegx\SafeRegex\Constants\PregConstants;
use TRegx\SafeRegex\Errors\HostError;
use TRegx\SafeRegex\Exception\Factory\RuntimeSafeRegexExceptionFactory;
use TRegx\SafeRegex\Exception\SafeRegexException;
use function preg_match;

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
}

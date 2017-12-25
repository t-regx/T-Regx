<?php
namespace SafeRegex;

use SafeRegex\Exception\CompileSafeRegexException;
use SafeRegex\Exception\RuntimeSafeRegexException;
use SafeRegex\Exception\ReturnFalseSafeRegexException;
use SafeRegex\Exception\SafeRegexException;

class ExceptionFactory
{
    /**
     * @param string $methodName
     * @param mixed $pregResult
     * @return void
     * @throws SafeRegexException
     */
    public function retrieveGlobalsAndThrow(string $methodName, $pregResult): void
    {
        $exception = $this->retrieveGlobalsAndReturn($methodName, $pregResult);

        if ($exception !== null) {
            throw $exception;
        }
    }

    /**
     * @param string $methodName
     * @param mixed $pregResult
     * @return SafeRegexException|null
     * @throws SafeRegexException
     */
    public function retrieveGlobalsAndReturn(string $methodName, $pregResult): ?SafeRegexException
    {
        $phpError = error_get_last();
        error_clear_last();

        return (new ExceptionFactory())->create($methodName, $pregResult, preg_last_error(), $phpError);
    }

    /**
     * @param string     $methodName
     * @param mixed      $pregResult
     * @param int        $runtimeError
     * @param array|null $phpError
     * @return SafeRegexException|null
     */
    public function create(string $methodName, $pregResult, int $runtimeError, ?array $phpError): ?SafeRegexException
    {
        if ($runtimeError !== PREG_NO_ERROR) {
            return new RuntimeSafeRegexException($methodName, $runtimeError);
        }

        if ($phpError !== null) {
            return new CompileSafeRegexException($methodName, PhpError::fromArray($phpError));
        }

        if ($pregResult === false) {
            return new ReturnFalseSafeRegexException($methodName, $pregResult);
        }

        return null;
    }
}

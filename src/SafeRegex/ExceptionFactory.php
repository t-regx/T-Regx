<?php
namespace SafeRegex;

use SafeRegex\Exception\PhpErrorSafeRegexException;
use SafeRegex\Exception\PregErrorSafeRegexException;
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
        $phpError = error_get_last();
        error_clear_last();

        (new ExceptionFactory())->createAndThrow($methodName, $pregResult, preg_last_error(), $phpError);
    }

    /**
     * @param string $methodName
     * @param mixed $pregResult
     * @param int $pregError
     * @param array|null $phpError
     * @return void
     * @throws SafeRegexException
     */
    public function createAndThrow(string $methodName, $pregResult, int $pregError, ?array $phpError): void
    {
        $exception = $this->create($methodName, $pregResult, $pregError, $phpError);

        if ($exception !== null) {
            throw $exception;
        }
    }

    /**
     * @param string $methodName
     * @param mixed $pregResult
     * @param int $pregError
     * @param array|null $phpError
     * @return SafeRegexException|null
     */
    public function create(string $methodName, $pregResult, int $pregError, ?array $phpError): ?SafeRegexException
    {
        if ($pregError !== PREG_NO_ERROR) {
            return new PregErrorSafeRegexException($methodName, $pregError);
        }

        if ($phpError !== null) {
            return new PhpErrorSafeRegexException($methodName, PhpError::fromArray($phpError));
        }

        if ($pregResult === false) {
            return new ReturnFalseSafeRegexException($methodName, $pregResult);
        }

        return null;
    }
}

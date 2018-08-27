<?php
namespace SafeRegex;

use SafeRegex\Errors\ErrorsCleaner;
use SafeRegex\Errors\FailureIndicators;
use SafeRegex\Exception\Factory\SuspectedReturnSafeRegexExceptionFactory;
use SafeRegex\Exception\SafeRegexException;

class ExceptionFactory
{
    /** @var FailureIndicators */
    private $failureIndicators;

    /** @var ErrorsCleaner */
    private $errorsCleaner;

    public function __construct()
    {
        $this->failureIndicators = new FailureIndicators();
        $this->errorsCleaner = new ErrorsCleaner();
    }

    /**
     * @param string $methodName
     * @param mixed  $pregResult
     * @return SafeRegexException|null
     */
    public function retrieveGlobals(string $methodName, $pregResult): ?SafeRegexException
    {
        $hostError = $this->errorsCleaner->getError();

        if ($hostError->occurred()) {
            return $hostError->getSafeRegexpException($methodName);
        }

        if ($this->failureIndicators->suspected($methodName, $pregResult)) {
            return (new SuspectedReturnSafeRegexExceptionFactory())->create($methodName, $pregResult);
        }

        return null;
    }
}

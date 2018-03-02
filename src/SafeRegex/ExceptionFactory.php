<?php
namespace SafeRegex;

use SafeRegex\Errors\ErrorsCleaner;
use SafeRegex\Errors\FailureIndicators;
use SafeRegex\Errors\HostError;
use SafeRegex\Exception\SafeRegexException;
use SafeRegex\Exception\SuspectedReturnSafeRegexException;

class ExceptionFactory
{
    /** @var FailureIndicators */
    private $failureIndicators;

    public function __construct()
    {
        $this->failureIndicators = new FailureIndicators();
    }

    /**
     * @param string $methodName
     * @param mixed  $pregResult
     * @return SafeRegexException|null
     */
    public function retrieveGlobals(string $methodName, $pregResult): ?SafeRegexException
    {
        return (new ExceptionFactory())->create($methodName, $pregResult, (new ErrorsCleaner())->getError());
    }

    private function create(string $methodName, $pregResult, ?HostError $hostError): ?SafeRegexException
    {
        if ($hostError->occurred()) {
            return $hostError->getSafeRegexpException($methodName);
        }

        if ($this->failureIndicators->suspected($methodName, $pregResult)) {
            return new SuspectedReturnSafeRegexException($methodName, $pregResult);
        }

        return null;
    }
}

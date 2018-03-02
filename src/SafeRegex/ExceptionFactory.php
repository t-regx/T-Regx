<?php
namespace SafeRegex;

use SafeRegex\Errors\ErrorsCleaner;
use SafeRegex\Errors\HostError;
use SafeRegex\Exception\ReturnFalseSafeRegexException;
use SafeRegex\Exception\SafeRegexException;

class ExceptionFactory
{
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

        if ($pregResult === false) {
            return new ReturnFalseSafeRegexException($methodName, $pregResult);
        }

        return null;
    }
}

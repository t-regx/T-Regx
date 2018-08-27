<?php
namespace SafeRegex\Exception\Factory;

use SafeRegex\Exception\SuspectedReturnSafeRegexException;

class SuspectedReturnSafeRegexExceptionFactory
{
    public function create(string $methodName, $returnValue)
    {
        return new SuspectedReturnSafeRegexException($methodName, var_export($returnValue, true));
    }
}

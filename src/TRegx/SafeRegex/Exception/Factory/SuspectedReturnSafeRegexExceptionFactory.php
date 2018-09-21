<?php
namespace TRegx\SafeRegex\Exception\Factory;

use TRegx\SafeRegex\Exception\SuspectedReturnSafeRegexException;

class SuspectedReturnSafeRegexExceptionFactory
{
    public function create(string $methodName, $returnValue): SuspectedReturnSafeRegexException
    {
        return new SuspectedReturnSafeRegexException($methodName, var_export($returnValue, true));
    }
}

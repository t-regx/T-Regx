<?php
namespace TRegx\SafeRegex\Exception\Factory;

use TRegx\SafeRegex\Exception\SuspectedReturnPregException;

class SuspectedReturnPregExceptionFactory
{
    public function create(string $methodName, $pattern, $returnValue): SuspectedReturnPregException
    {
        return new SuspectedReturnPregException($methodName, $pattern, \var_export($returnValue, true));
    }
}

<?php
namespace TRegx\SafeRegex\Exception\Factory;

use TRegx\SafeRegex\Exception\SuspectedReturnPregException;

class SuspectedReturnPregExceptionFactory
{
    public function create(string $methodName, $returnValue): SuspectedReturnPregException
    {
        return new SuspectedReturnPregException($methodName, \var_export($returnValue, true));
    }
}

<?php
namespace TRegx\SafeRegex\Exception;

class InvalidReturnValueException extends PregException
{
    public function __construct($methodName, $pattern, $returnType)
    {
        parent::__construct($methodName, $pattern, "Invalid $methodName() callback return type. Expected type that can be cast to string, but $returnType given");
    }
}

<?php
namespace TRegx\SafeRegex\Exception;

class InvalidReturnValueException extends PregException
{
    public function __construct($methodName, $returnType)
    {
        parent::__construct($methodName, "Invalid $methodName() callback return type. Expected type that can be cast to string, but $returnType given");
    }
}

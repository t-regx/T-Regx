<?php
namespace TRegx\SafeRegex\Exception;

class InvalidReturnValueException extends PregException
{
    public function __construct($pattern, string $methodName, $returnType)
    {
        parent::__construct("Invalid $methodName() callback return type. Expected type that can be cast to string, but $returnType given", $pattern, $methodName);
    }
}

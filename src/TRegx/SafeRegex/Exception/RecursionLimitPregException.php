<?php
namespace TRegx\SafeRegex\Exception;

class RecursionLimitPregException extends RuntimePregException
{
    public function __construct(string $methodName, $pattern, string $message, int $errorCode, string $errorName)
    {
        parent::__construct($methodName, $pattern, $message, $errorCode, $errorName);
    }
}

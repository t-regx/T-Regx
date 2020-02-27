<?php
namespace TRegx\SafeRegex\Exception;

class RecursionLimitPregException extends RuntimePregException
{
    public function __construct(string $methodName, string $message, int $errorCode, string $errorName)
    {
        parent::__construct($methodName, $message, $errorCode, $errorName);
    }
}

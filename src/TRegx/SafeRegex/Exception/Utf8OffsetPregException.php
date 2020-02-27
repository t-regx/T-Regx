<?php
namespace TRegx\SafeRegex\Exception;

class Utf8OffsetPregException extends RuntimePregException
{
    public function __construct(string $methodName, string $message, int $errorCode, string $errorName)
    {
        parent::__construct($methodName, "Invalid UTF-8 offset parameter was passed to $methodName().", $errorCode, $errorName);
    }
}

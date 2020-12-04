<?php
namespace TRegx\SafeRegex\Exception;

class Utf8OffsetPregException extends RuntimePregException
{
    public function __construct(string $message, $pattern, string $methodName, int $errorCode, string $errorName)
    {
        parent::__construct("Invalid UTF-8 offset parameter was passed to $methodName().", $pattern, $methodName, $errorCode, $errorName);
    }
}

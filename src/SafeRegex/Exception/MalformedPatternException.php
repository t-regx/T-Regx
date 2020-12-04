<?php
namespace TRegx\SafeRegex\Exception;

use TRegx\SafeRegex\PhpError;

class MalformedPatternException extends CompilePregException
{
    public function __construct(string $methodName, $pattern, string $message, PhpError $error, string $errorName)
    {
        parent::__construct($methodName, $pattern, $message, $error, $errorName);
    }
}

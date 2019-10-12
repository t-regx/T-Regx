<?php
namespace TRegx\SafeRegex\Exception;

use TRegx\SafeRegex\Exception\CompileSafeRegexException;
use TRegx\SafeRegex\PhpError;

class MalformedPatternException extends CompileSafeRegexException
{
    public function __construct(string $methodName, string $message, PhpError $error, string $errorName)
    {
        parent::__construct($methodName, $message, $error, $errorName);
    }
}

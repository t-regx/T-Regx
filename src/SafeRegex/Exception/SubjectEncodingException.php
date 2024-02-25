<?php
namespace TRegx\SafeRegex\Exception;

/**
 * @deprecated
 */
class SubjectEncodingException extends RuntimePregException
{
    public function __construct($pattern, string $methodName, int $errorCode, string $errorName)
    {
        parent::__construct("After invoking $methodName(), preg_last_error() returned $errorName",
            $pattern, $methodName, $errorCode, $errorName);
    }
}

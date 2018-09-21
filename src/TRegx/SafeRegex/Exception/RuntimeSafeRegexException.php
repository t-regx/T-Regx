<?php
namespace TRegx\SafeRegex\Exception;

class RuntimeSafeRegexException extends SafeRegexException
{
    /** @var int */
    private $errorCode;
    /** @var string */
    private $errorName;

    public function __construct(string $message, string $methodName, int $errorCode, string $errorName)
    {
        parent::__construct($methodName, $message);
        $this->errorCode = $errorCode;
        $this->errorName = $errorName;
    }

    public function getError(): int
    {
        return $this->errorCode;
    }

    public function getErrorName(): string
    {
        return $this->errorName;
    }
}

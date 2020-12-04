<?php
namespace TRegx\SafeRegex\Exception;

class RuntimePregException extends PregException
{
    /** @var int */
    private $errorCode;
    /** @var string */
    private $errorName;

    public function __construct(string $message, $pattern, string $methodName, int $errorCode, string $errorName)
    {
        parent::__construct($message, $pattern, $methodName);
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

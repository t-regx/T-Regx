<?php
namespace TRegx\SafeRegex\Exception;

class RuntimePregException extends PregException
{
    /** @var int */
    private $errorCode;
    /** @var string */
    private $errorName;

    public function __construct(string $methodName, $pattern, string $message, int $errorCode, string $errorName)
    {
        parent::__construct($methodName, $pattern, $message);
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

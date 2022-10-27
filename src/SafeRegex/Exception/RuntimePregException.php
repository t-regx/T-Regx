<?php
namespace TRegx\SafeRegex\Exception;

class RuntimePregException extends \RuntimeException implements PregException
{
    /** @var string */
    private $methodName;
    /** @var string|string[] */
    private $pattern;
    /** @var int */
    private $errorCode;
    /** @var string */
    private $errorName;

    public function __construct(string $message, $pattern, string $methodName, int $errorCode, string $errorName)
    {
        parent::__construct($message);
        $this->methodName = $methodName;
        $this->pattern = $pattern;
        $this->errorCode = $errorCode;
        $this->errorName = $errorName;
    }

    public function getInvokingMethod(): string
    {
        return $this->methodName;
    }

    /**
     * @return string|string[]
     */
    public function getPregPattern()
    {
        return $this->pattern;
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

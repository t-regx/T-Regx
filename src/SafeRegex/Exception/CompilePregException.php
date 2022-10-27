<?php
namespace TRegx\SafeRegex\Exception;

use TRegx\SafeRegex\Internal\PhpError;

class CompilePregException extends \RuntimeException implements PregException
{
    /** @var PhpError */
    private $error;
    /** @var string */
    private $errorName;
    /** @var string */
    private $methodName;
    /** @var string|string[] */
    private $pattern;

    public function __construct(string $methodName, $pattern, string $message, PhpError $error, string $errorName)
    {
        parent::__construct($message);
        $this->methodName = $methodName;
        $this->pattern = $pattern;
        $this->error = $error;
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
        return $this->error->getType();
    }

    public function getErrorName(): string
    {
        return $this->errorName;
    }

    public function getPregErrorMessage(): string
    {
        return $this->error->getMessage();
    }
}

<?php
namespace TRegx\SafeRegex\Exception;

use TRegx\SafeRegex\PhpError;

class CompilePregException extends PregException
{
    /** @var PhpError */
    private $error;
    /** @var string */
    private $errorName;

    public function __construct(string $methodName, $pattern, string $message, PhpError $error, string $errorName)
    {
        parent::__construct($methodName, $pattern, $message);
        $this->error = $error;
        $this->errorName = $errorName;
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

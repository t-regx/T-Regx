<?php
namespace Danon\SafeRegex;

class PregReturnException extends \Exception
{
    /** @var string */
    private $error;

    public function __construct(int $errorCode, string $methodName)
    {
        $this->error = $errorCode;
        parent::__construct("$methodName() returned 'false' value.");
    }

    public function getErrorCode(): int
    {
        return $this->error;
    }

    public function getError(): string
    {
        return preg::error_constant($this->error);
    }
}

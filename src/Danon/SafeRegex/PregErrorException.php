<?php
namespace Danon\SafeRegex;

class PregErrorException extends \Exception
{
    /** @var int */
    private $errorCode;

    public function __construct(int $errorCode, string $methodName)
    {
        $this->errorCode = $errorCode;
        $error = self::getError();
        parent::__construct("After invoking $methodName(), preg_last_error() returned $error.");
    }

    public function getErrorCode(): int
    {
        return $this->errorCode;
    }

    public function getError(): string
    {
        return preg::error_constant($this->errorCode);
    }
}

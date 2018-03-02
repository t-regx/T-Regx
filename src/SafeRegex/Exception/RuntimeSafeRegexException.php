<?php
namespace SafeRegex\Exception;

use SafeRegex\Constants\PregConstants;

class RuntimeSafeRegexException extends SafeRegexException
{
    /** @var int */
    private $errorCode;

    public function __construct(string $methodName, int $errorCode)
    {
        $this->errorCode = $errorCode;
        $errorMessage = self::getErrorName();
        parent::__construct("After invoking $methodName(), preg_last_error() returned $errorMessage.");
    }

    public function getError(): int
    {
        return $this->errorCode;
    }

    public function getErrorName(): string
    {
        return (new PregConstants())->getConstant($this->errorCode);
    }
}

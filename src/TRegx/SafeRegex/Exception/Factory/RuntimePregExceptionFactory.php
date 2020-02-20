<?php
namespace TRegx\SafeRegex\Exception\Factory;

use TRegx\SafeRegex\Constants\PregConstants;
use TRegx\SafeRegex\Exception\RuntimePregException;

class RuntimePregExceptionFactory
{
    /** @var PregConstants */
    private $pregConstants;
    /** @var string */
    private $methodName;
    /** @var int */
    private $errorCode;

    public function __construct(string $methodName, int $errorCode)
    {
        $this->pregConstants = new PregConstants();
        $this->methodName = $methodName;
        $this->errorCode = $errorCode;
    }

    public function create(): RuntimePregException
    {
        return $this->instantiateException($this->getErrorName());
    }

    public function getErrorName(): string
    {
        return $this->pregConstants->getConstant($this->errorCode);
    }

    public function instantiateException(string $errorName): RuntimePregException
    {
        return new RuntimePregException($this->getExceptionMessage($errorName), $this->methodName, $this->errorCode, $errorName);
    }

    public function getExceptionMessage(string $errorText): string
    {
        return "After invoking $this->methodName(), preg_last_error() returned $errorText.";
    }
}

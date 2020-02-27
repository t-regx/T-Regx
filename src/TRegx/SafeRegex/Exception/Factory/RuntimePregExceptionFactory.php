<?php
namespace TRegx\SafeRegex\Exception\Factory;

use TRegx\SafeRegex\Constants\PregConstants;
use TRegx\SafeRegex\Exception\BacktrackLimitPregException;
use TRegx\SafeRegex\Exception\JitStackLimitPregException;
use TRegx\SafeRegex\Exception\RecursionLimitPregException;
use TRegx\SafeRegex\Exception\RuntimePregException;
use TRegx\SafeRegex\Exception\SubjectEncodingPregException;
use TRegx\SafeRegex\Exception\Utf8OffsetPregException;

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

    private function getErrorName(): string
    {
        return $this->pregConstants->getConstant($this->errorCode);
    }

    private function instantiateException(string $errorName): RuntimePregException
    {
        $class = $this->className();
        return new $class($this->methodName, $this->getExceptionMessage($errorName), $this->errorCode, $errorName);
    }

    private function className(): string
    {
        $classes = [
            PREG_BAD_UTF8_ERROR        => SubjectEncodingPregException::class,
            PREG_BAD_UTF8_OFFSET_ERROR => Utf8OffsetPregException::class,
            PREG_BACKTRACK_LIMIT_ERROR => BacktrackLimitPregException::class,
            PREG_RECURSION_LIMIT_ERROR => RecursionLimitPregException::class,
            PREG_JIT_STACKLIMIT_ERROR  => JitStackLimitPregException::class
        ];
        return $classes[$this->errorCode] ?? RuntimePregException::class;
    }

    private function getExceptionMessage(string $errorText): string
    {
        return "After invoking $this->methodName(), preg_last_error() returned $errorText.";
    }
}

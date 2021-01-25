<?php
namespace TRegx\SafeRegex\Exception\Factory;

use TRegx\SafeRegex\Constants\PregConstants;
use TRegx\SafeRegex\Exception\CatastrophicBacktrackingException;
use TRegx\SafeRegex\Exception\JitStackLimitException;
use TRegx\SafeRegex\Exception\RecursionException;
use TRegx\SafeRegex\Exception\RuntimePregException;
use TRegx\SafeRegex\Exception\SubjectEncodingException;
use TRegx\SafeRegex\Exception\UnicodeOffsetException;

class RuntimePregExceptionFactory
{
    /** @var PregConstants */
    private $pregConstants;
    /** @var string */
    private $methodName;
    /** @var string|array */
    private $pattern;
    /** @var int */
    private $errorCode;

    public function __construct(string $methodName, $pattern, int $errorCode)
    {
        $this->pregConstants = new PregConstants();
        $this->methodName = $methodName;
        $this->pattern = $pattern;
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
        return new $class($this->getExceptionMessage($errorName), $this->pattern, $this->methodName, $this->errorCode, $errorName);
    }

    private function className(): string
    {
        $classes = [
            \PREG_BAD_UTF8_ERROR        => SubjectEncodingException::class,
            \PREG_BAD_UTF8_OFFSET_ERROR => UnicodeOffsetException::class,
            \PREG_BACKTRACK_LIMIT_ERROR => CatastrophicBacktrackingException::class,
            \PREG_RECURSION_LIMIT_ERROR => RecursionException::class,
            \PREG_JIT_STACKLIMIT_ERROR  => JitStackLimitException::class
        ];
        return $classes[$this->errorCode] ?? RuntimePregException::class;
    }

    private function getExceptionMessage(string $errorText): string
    {
        return "After invoking $this->methodName(), preg_last_error() returned $errorText";
    }
}

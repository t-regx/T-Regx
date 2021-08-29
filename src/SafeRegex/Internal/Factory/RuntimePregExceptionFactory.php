<?php
namespace TRegx\SafeRegex\Internal\Factory;

use TRegx\SafeRegex\Exception\CatastrophicBacktrackingException;
use TRegx\SafeRegex\Exception\JitStackLimitException;
use TRegx\SafeRegex\Exception\RecursionException;
use TRegx\SafeRegex\Exception\RuntimePregException;
use TRegx\SafeRegex\Exception\SubjectEncodingException;
use TRegx\SafeRegex\Exception\UnicodeOffsetException;
use TRegx\SafeRegex\Internal\Constants\PregConstants;

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
        return $this->instantiateException($this->pregConstants->getConstant($this->errorCode));
    }

    private function instantiateException(string $errorName): RuntimePregException
    {
        if ($this->errorCode === \PREG_BAD_UTF8_ERROR) {
            return new SubjectEncodingException($this->pattern, $this->methodName, $this->errorCode, $errorName);
        }
        if ($this->errorCode === \PREG_BAD_UTF8_OFFSET_ERROR) {
            return new UnicodeOffsetException($this->pattern, $this->methodName, $this->errorCode, $errorName);
        }
        if ($this->errorCode === \PREG_BACKTRACK_LIMIT_ERROR) {
            return new CatastrophicBacktrackingException($this->pattern, $this->methodName, $this->errorCode, $errorName);
        }
        if ($this->errorCode === \PREG_RECURSION_LIMIT_ERROR) {
            return new RecursionException($this->pattern, $this->methodName, $this->errorCode, $errorName);
        }
        if ($this->errorCode === \PREG_JIT_STACKLIMIT_ERROR) {
            return new JitStackLimitException($this->pattern, $this->methodName, $this->errorCode, $errorName);
        }
        return new RuntimePregException("After invoking $this->methodName(), preg_last_error() returned $errorName",
            $this->pattern, $this->methodName, $this->errorCode, $errorName);
    }
}

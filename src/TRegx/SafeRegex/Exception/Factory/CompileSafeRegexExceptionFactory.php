<?php
namespace TRegx\SafeRegex\Exception\Factory;

use TRegx\SafeRegex\Constants\PhpErrorConstants;
use TRegx\SafeRegex\Exception\CompileSafeRegexException;
use TRegx\SafeRegex\PhpError;

class CompileSafeRegexExceptionFactory
{
    /** @var PhpErrorConstants */
    private $phpErrorConstants;
    /** @var string */
    private $methodName;
    /** @var PhpError */
    private $error;

    public function __construct(string $methodName, PhpError $error)
    {
        $this->phpErrorConstants = new PhpErrorConstants();
        $this->methodName = $methodName;
        $this->error = $error;
    }

    public function create(): CompileSafeRegexException
    {
        return $this->instantiateException($this->getErrorName());
    }

    public function getErrorName(): string
    {
        return $this->phpErrorConstants->getConstant($this->error->getType());
    }

    public function instantiateException(string $errorName): CompileSafeRegexException
    {
        return new CompileSafeRegexException($this->methodName, $this->getExceptionMessage($errorName), $this->error, $errorName);
    }

    public function getExceptionMessage(string $errorName): string
    {
        return $this->error->getMessage() . PHP_EOL . ' ' . PHP_EOL . "(caused by $errorName)";
    }
}

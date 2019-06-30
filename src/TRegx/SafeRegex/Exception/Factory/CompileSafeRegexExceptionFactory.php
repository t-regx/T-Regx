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
        return new CompileSafeRegexException(
            $this->methodName,
            $this->error->getMessage(),
            $this->error,
            $this->phpErrorConstants->getConstant($this->error->getType()));
    }
}

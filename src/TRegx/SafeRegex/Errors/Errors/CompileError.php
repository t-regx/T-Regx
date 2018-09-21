<?php
namespace TRegx\SafeRegex\Errors\Errors;

use TRegx\CleanRegex\Exception\CleanRegex\InternalCleanRegexException;
use TRegx\SafeRegex\Errors\HostError;
use TRegx\SafeRegex\Exception\Factory\CompileSafeRegexExceptionFactory;
use TRegx\SafeRegex\Exception\SafeRegexException;
use TRegx\SafeRegex\PhpError;

abstract class CompileError implements HostError
{
    /** @var PhpError|null */
    private $error;

    public function __construct(?PhpError $error)
    {
        $this->error = $error;
    }

    protected function getError(): ?PhpError
    {
        return $this->error;
    }

    public function getSafeRegexpException(string $methodName): SafeRegexException
    {
        if ($this->occurred()) {
            return (new CompileSafeRegexExceptionFactory($methodName, $this->error))->create();
        }
        throw new InternalCleanRegexException();
    }

    public static function getLast(): CompileError
    {
        $phpError = PhpError::getLast();
        if (StandardCompileError::isCompatible()) {
            return new StandardCompileError($phpError);
        }
        return new OvertriggerCompileError($phpError);
    }
}

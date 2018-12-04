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
    protected $error;

    public function __construct(?PhpError $error)
    {
        $this->error = $error;
    }

    public abstract function occurred(): bool;

    public abstract function clear(): void;

    public function getSafeRegexpException(string $methodName): SafeRegexException
    {
        if ($this->occurred()) {
            return (new CompileSafeRegexExceptionFactory($methodName, $this->error))->create();
        }
        throw new InternalCleanRegexException();
    }
}

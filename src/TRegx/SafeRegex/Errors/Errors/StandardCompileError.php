<?php
namespace TRegx\SafeRegex\Errors\Errors;

use TRegx\SafeRegex\Exception\Factory\CompilePregExceptionFactory;
use TRegx\SafeRegex\Exception\PregException;
use TRegx\SafeRegex\PhpError;
use function error_clear_last;

class StandardCompileError implements CompileError
{
    /** @var PhpError|null */
    protected $error;

    public function __construct(?PhpError $error)
    {
        $this->error = $error;
    }

    public function occurred(): bool
    {
        return $this->error !== null;
    }

    public function clear(): void
    {
        error_clear_last();
    }

    public function getSafeRegexpException(string $methodName): PregException
    {
        return (new CompilePregExceptionFactory($methodName, $this->error))->create();
    }
}

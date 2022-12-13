<?php
namespace TRegx\SafeRegex\Internal\Errors;

use TRegx\SafeRegex\Exception\PregException;
use TRegx\SafeRegex\Internal\Factory\CompilePregExceptionFactory;
use TRegx\SafeRegex\Internal\PhpError;

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

    /**
     * @param string $methodName
     * @param string|string[] $pattern
     * @return PregException
     */
    public function getSafeRegexpException(string $methodName, $pattern): PregException
    {
        return (new CompilePregExceptionFactory($methodName, $pattern, $this->error))->create();
    }
}

<?php
namespace TRegx\SafeRegex\Errors\Errors;

use TRegx\SafeRegex\Exception\Factory\CompileSafeRegexExceptionFactory;
use TRegx\SafeRegex\Exception\SafeRegexException;
use TRegx\SafeRegex\PhpError;
use function error_clear_last;
use function is_callable;

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

    public static function isCompatible(): bool
    {
        return is_callable('error_clear_last');
    }

    public function getSafeRegexpException(string $methodName): SafeRegexException
    {
        return (new CompileSafeRegexExceptionFactory($methodName, $this->error))->create();
    }
}

<?php
namespace TRegx\SafeRegex\Errors\Errors;

use TRegx\SafeRegex\Exception\Factory\CompileSafeRegexExceptionFactory;
use TRegx\SafeRegex\Exception\SafeRegexException;
use TRegx\SafeRegex\PhpError;
use function trigger_error;

class OvertriggerCompileError implements CompileError
{
    public const OVERTRIGGER_MESSAGE = 'SafeRegex triggered over previous exception, to prevent it from causing exceptions for current invocation';

    /** @var PhpError|null */
    protected $error;

    public function __construct(?PhpError $error)
    {
        $this->error = $error;
    }

    public function occurred(): bool
    {
        if ($this->error === null) {
            return false;
        }
        return $this->hasOvertriggerMessage();
    }

    private function hasOvertriggerMessage(): bool
    {
        return $this->error->getMessage() !== self::OVERTRIGGER_MESSAGE;
    }

    public function clear(): void
    {
        @trigger_error(self::OVERTRIGGER_MESSAGE, E_USER_WARNING);
    }

    public function getSafeRegexpException(string $methodName): SafeRegexException
    {
        return (new CompileSafeRegexExceptionFactory($methodName, $this->error))->create();
    }
}

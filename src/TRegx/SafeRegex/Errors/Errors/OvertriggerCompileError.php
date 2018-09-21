<?php
namespace TRegx\SafeRegex\Errors\Errors;

class OvertriggerCompileError extends CompileError
{
    public const OVERTRIGGER_MESSAGE = 'SafeRegex triggered over previous exception, to prevent it from causing exceptions for current invocation';

    public function occurred(): bool
    {
        if ($this->error === null) {
            return false;
        }

        return $this->error->getMessage() !== self::OVERTRIGGER_MESSAGE;
    }

    public function clear(): void
    {
        @trigger_error(self::OVERTRIGGER_MESSAGE, E_USER_WARNING);
    }
}

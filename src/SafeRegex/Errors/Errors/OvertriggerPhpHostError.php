<?php
namespace SafeRegex\Errors\Errors;

use SafeRegex\PhpError;

class OvertriggerPhpHostError extends PhpHostError
{
    const OVERTRIGGER_MESSAGE = 'SafeRegex w triggered over previous exception, to prevent it from causing exceptions for current invokation';

    /** @var PhpError|null */
    private $error;

    public function __construct(?PhpError $error)
    {
        $this->error = $error;
    }

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

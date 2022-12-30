<?php
namespace Test\Utils;

use PHPUnit\Framework\Assert;

class ErrorHandlerAssertion
{
    /** @var string */
    private $message = 'Error handler was not supposed to be altered';

    public function __construct()
    {
        \set_error_handler(function (int $code, string $message): bool {
            if ($this->message === $message) {
                throw new \LogicException();
            }
            return false;
        });
    }

    public function assertOperational(): void
    {
        $operational = $this->operational();
        \restore_error_handler();
        Assert::assertTrue($operational, 'Failed to assert that error handler is operational');
    }

    private function operational(): bool
    {
        try {
            @\trigger_error($this->message, \E_USER_WARNING);
            return false;
        } catch (\LogicException $exception) {
            return true;
        }
    }
}

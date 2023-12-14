<?php
namespace Test\Fixture;

use PHPUnit\Framework\Assert;

class HandlerSnapshot
{
    private string $message = 'Error handler was not supposed to be altered';

    public function __construct()
    {
        \set_error_handler(function (int $code, string $message): bool {
            if ($this->message === $message) {
                throw new \LogicException();
            }
            return false;
        });
    }

    public function assertEquals(): void
    {
        $overridden = $this->handlerOverridden();
        \restore_error_handler();
        Assert::assertFalse($overridden, 'Failed to assert that error handler is not overridden.');
    }

    private function handlerOverridden(): bool
    {
        try {
            @\trigger_error($this->message, \E_USER_WARNING);
            return true;
        } catch (\LogicException $exception) {
            return false;
        }
    }
}

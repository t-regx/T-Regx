<?php
namespace Test\Fixture;

use PHPUnit\Framework\Assert;

class WarningSnapshot
{
    public function __construct()
    {
        \set_error_handler(null);
        try {
            @\trigger_error('warning-snapshot', \E_USER_WARNING);
        } finally {
            \restore_error_handler();
        }
    }

    public function assertEquals(): void
    {
        $error = \error_get_last();
        \error_clear_last();
        Assert::assertNotNull($error, 'Failed to assert that last error is preserved.');
        Assert::assertSame('warning-snapshot', $error['message']);
        Assert::assertSame(\E_USER_WARNING, $error['type']);
    }

    public function clear(): void
    {
        \error_clear_last();
    }
}

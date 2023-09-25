<?php
namespace Test\Fixture\Exception;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\AssertionFailedError;

class ExceptionAssertion
{
    private \Throwable $throwable;

    public function __construct(\Throwable $throwable)
    {
        $this->throwable = $throwable;
    }

    public function get(): \Throwable
    {
        return $this->throwable;
    }

    public function assertMessage(string $message): void
    {
        Assert::assertSame($message, $this->throwable->getMessage(),
            'Failed to assert the exact exception message.');
    }

    public function assertMessageStartsWith(string $prefix): void
    {
        $this->showDifference($prefix,
            'Failed to assert exception message prefix.',
            fn() => Assert::assertStringStartsWith($prefix, $this->throwable->getMessage()));
    }

    private function showDifference(string $actual, string $message, callable $assertion): void
    {
        /*
         * Intercept default PhpUnit string assertion,
         * because it produces unreadable output on failure.
         */
        try {
            $assertion();
        } catch (AssertionFailedError $error) {
            Assert::assertSame($actual, $this->throwable->getMessage(), $message);
        }
    }
}

<?php
namespace Test\Fixture\Exception;

use PHPUnit\Framework\Assert;

class ExceptionAssertion
{
    private \Throwable $throwable;

    public function __construct(\Throwable $throwable)
    {
        $this->throwable = $throwable;
    }

    public function assertMessage(string $message): void
    {
        Assert::assertSame($message, $this->throwable->getMessage(),
            'Failed to assert the exact exception message.');
    }
}

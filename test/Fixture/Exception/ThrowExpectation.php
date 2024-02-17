<?php
namespace Test\Fixture\Exception;

use PHPUnit\Framework\Assert;

class ThrowExpectation
{
    private ?\Throwable $throwable;

    public function __construct(callable $block)
    {
        $this->throwable = $this->thrown($block);
    }

    private function thrown(callable $block): ?\Throwable
    {
        try {
            $block();
            return null;
        } catch (\Throwable $throwable) {
            return $throwable;
        }
    }

    public function assertException(string $exceptionClass): ExceptionAssertion
    {
        if (!\class_exists($exceptionClass)) {
            throw new \Exception("Class '$exceptionClass' does not exist.");
        }
        if ($this->throwable === null) {
            Assert::fail("Failed to assert that exception was thrown: $exceptionClass");
        }
        if ($exceptionClass === \get_class($this->throwable)) {
            Assert::assertTrue(true);
            return new ExceptionAssertion($this->throwable);
        }
        throw $this->throwable;
    }

    public function assertExceptionNone(): void
    {
        if ($this->throwable === null) {
            Assert::assertTrue(true);
        } else {
            throw $this->throwable;
        }
    }

    public function get(): \Throwable
    {
        return $this->throwable;
    }
}

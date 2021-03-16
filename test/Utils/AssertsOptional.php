<?php
namespace Test\Utils;

use AssertionError;
use PHPUnit\Framework\Assert;
use TRegx\CleanRegex\Match\Optional;

trait AssertsOptional
{
    public function assertOptionalEmpty(Optional $optional): void
    {
        if (!$this->isOptionalEmpty($optional)) {
            Assert::fail("Failed to assert that optional is empty");
        } else {
            Assert::assertTrue(true);
        }
    }

    public function assertOptionalHas($expected, Optional $optional): void
    {
        if ($this->isOptionalEmpty($optional)) {
            Assert::fail("Failed to assert that optional is not empty");
        }
        Assert::assertSame($expected, $optional->orThrow());
    }

    private function isOptionalEmpty(Optional $optional): bool
    {
        $calls = $this->calls($optional);
        $returns = $this->returns($optional);
        $threw = $this->threw($optional);
        if ($calls && $returns && $threw) {
            return true;
        }
        if (!$calls && !$returns && !$threw) {
            return false;
        }
        throw new AssertionError("Inconsistent optional implementation");
    }

    private function returns(Optional $optional): bool
    {
        return $optional->orReturn(null) === null && $optional->orReturn(true) === true;
    }

    private function threw(Optional $optional): bool
    {
        try {
            $optional->orThrow(AssertionError::class);
            return false;
        } catch (AssertionError $exception) {
            return true;
        }
    }

    private function calls(Optional $optional): bool
    {
        $called = false;
        $optional->orElse(function () use (&$called) {
            $called = true;
        });
        return $called;
    }
}

<?php
namespace Test\Utils\TestCase;

use Exception;

trait TestCaseConditional
{
    public abstract function expectException(string $exception): void;

    /**
     * This test really is marked as unncessary. If the condition is not met,
     * I simply want to skip this test. Not mark it as risky, incomplete or skipped,
     * because it's not.
     *
     * This test simply doesn't make sense, if the condition is not met.
     */
    public function markTestUnnecessary(string $message): void
    {
        $this->expectException(Exception::class);
        throw new Exception($message);
    }
}

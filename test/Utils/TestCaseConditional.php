<?php
namespace Test\Utils;

use Exception;

trait TestCaseConditional
{
    public abstract function expectException(string $exception): void;

    public function markTestRenderedUnnecessary(string $message): void
    {
        $this->expectException(Exception::class);
        throw new Exception($message);
    }
}

<?php
namespace Test\Utils;

trait AssertsHasClass
{
    abstract static public function assertSame($expected, $actual, string $message = ''): void;

    private function assertHasClass(string $expected, \Throwable $exception): void
    {
        // Don't use "instanceof", $exception must be this class exactly
        $this->assertSame($expected, \get_class($exception));
    }
}

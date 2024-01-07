<?php
namespace Test\Unit;

use PHPUnit\Framework\TestCase;
use Regex\ExecutionException;
use Regex\Pattern;
use function Test\Fixture\Functions\catching;

class _executionError extends TestCase
{
    /**
     * @test
     * @runInSeparateProcess
     */
    public function test()
    {
        catching(fn() => new Pattern('(?=word\C)', 'u'))
            ->assertException(ExecutionException::class)
            ->assertMessage('Allocation of JIT memory failed, PCRE JIT will be disabled.');
    }
}

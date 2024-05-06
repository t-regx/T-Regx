<?php
namespace Test\Unit\_matchError;

use PHPUnit\Framework\TestCase;
use Regex\BacktrackException;
use Regex\Pattern;
use Regex\RecursionException;
use function Test\Fixture\Functions\catching;

class TypesTest extends TestCase
{

    /**
     * @test
     */
    public function recursion()
    {
        $pattern = new Pattern('(*NO_JIT)(*LIMIT_RECURSION=3)((((motive))))');
        catching(fn() => $pattern->test('A man with no motive is a man no one suspects'))
            ->assertException(RecursionException::Class)
            ->assertMessage('Recursion depth limit exceeded when matching the subject.');
    }

    /**
     * @test
     */
    public function backtracking()
    {
        $pattern = new Pattern('(\d+\d+)+3');
        catching(fn() => $pattern->test('11111111111111111111 3'))
            ->assertException(BacktrackException::class)
            ->assertMessage('Catastrophic backtracking occurred when matching the subject.');
    }
}

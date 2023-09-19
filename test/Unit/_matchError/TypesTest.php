<?php
namespace Test\Unit\_matchError;

use PHPUnit\Framework\TestCase;
use Regex\BacktrackException;
use Regex\JitException;
use Regex\Pattern;
use Regex\RecursionException;
use Regex\UnicodeException;
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

    /**
     * @test
     */
    public function jit()
    {
        $pattern = new Pattern('^(foo)+$');
        $subject = \str_repeat('foo', 1024 * 8192);
        catching(fn() => $pattern->test($subject))
            ->assertException(JitException::class)
            ->assertMessage('Just-in-time compilation stack limit exceeded when executing the pattern.');
    }

    /**
     * @test
     */
    public function subjectEncoding()
    {
        $pattern = new Pattern('\w+', 'u');
        catching(fn() => $pattern->test("\xe2\x28\xa1"))
            ->assertException(UnicodeException::class)
            ->assertMessage('Malformed unicode subject.');
    }
}

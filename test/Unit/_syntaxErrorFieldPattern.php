<?php
namespace Test\Unit;

use PHPUnit\Framework\TestCase;
use Regex\Pattern;
use Regex\SyntaxException;
use Test\Fixture\Exception\ThrowExpectation;
use function Test\Fixture\Functions\catching;
use function Test\Fixture\Functions\since;

class _syntaxErrorFieldPattern extends TestCase
{
    public function test()
    {
        $exception = $this->catching(fn() => new Pattern('+ invalid', 'n'));
        $this->assertSame('+ invalid', $exception->syntaxErrorPattern);
    }

    /**
     * @test
     */
    public function trailingBackslash()
    {
        $exception = $this->catching(fn() => new Pattern('pattern\\'));
        $this->assertSame('pattern\\', $exception->syntaxErrorPattern);
    }

    /**
     * @test
     */
    public function nullByte()
    {
        // when
        $call = catching(fn() => new Pattern("\w\d\0", 'n'));
        // then
        if (since('8.2.0')) {
            $call->assertExceptionNone();;
        } else {
            $exception = $this->patternException($call);
            $this->assertSame("\w\d\0", $exception->syntaxErrorPattern);
        }
    }

    private function catching(callable $block): SyntaxException
    {
        return $this->patternException(catching($block));
    }

    private function patternException(ThrowExpectation $expectation): SyntaxException
    {
        /** @var SyntaxException $exception */
        $exception = $expectation
            ->assertException(SyntaxException::class)
            ->get();
        return $exception;
    }
}

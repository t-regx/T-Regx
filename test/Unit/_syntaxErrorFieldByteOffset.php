<?php
namespace Test\Unit;

use PHPUnit\Framework\TestCase;
use Regex\Pattern;
use Regex\SyntaxException;
use Test\Fixture\Exception\ThrowExpectation;
use function Test\Fixture\Functions\catching;
use function Test\Fixture\Functions\since;

class _syntaxErrorFieldByteOffset extends TestCase
{
    public function test()
    {
        $this->assertByteOffset(4, fn() => new Pattern('(?i)+ invalid'));
    }

    /**
     * @test
     */
    public function trailingBackslash()
    {
        $this->assertByteOffset(7, fn() => new Pattern('pattern\\'));
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
            $this->assertSame(4, $exception->syntaxErrorByteOffset);
        }
    }

    /**
     * @test
     */
    public function optionSetting()
    {
        $this->assertByteOffset(7, fn() => new Pattern('invalid)', 'n'));
        $this->assertByteOffset(12, fn() => new Pattern('(?n) invalid)'));
    }

    /**
     * @test
     */
    public function optionSettingVerb()
    {
        $this->assertByteOffset(13, fn() => new Pattern('(*CRLF)(*ANY)+invalid', 'n'));
        $this->assertByteOffset(13, fn() => new Pattern('(*CRLF)(*ANY)+invalid'));
    }

    /**
     * @test
     */
    public function optionSettingVerbInvalid()
    {
        $this->assertByteOffset(9, fn() => new Pattern('(*INVALID)(*ANY)+invalid', 'n'));
        $this->assertByteOffset(9, fn() => new Pattern('(*INVALID)(*ANY)+invalid'));
    }

    private function assertByteOffset(int $byteOffset, callable $block): void
    {
        $exception = $this->patternException(catching($block));
        $this->assertSame($byteOffset, $exception->syntaxErrorByteOffset);
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

<?php
namespace Test\Unit;

use PHPUnit\Framework\TestCase;
use Regex\Pattern;
use Regex\SyntaxException;
use Regex\UnicodeException;
use function Test\Fixture\Functions\catching;

class _syntaxErrorFieldOffset extends TestCase
{
    public function test()
    {
        try {
            new Pattern('(?i)+ invalid');
        } catch (SyntaxException $exception) {
            $this->assertSame(4, $exception->syntaxErrorOffset());
        }
    }

    /**
     * @test
     */
    public function unicode()
    {
        try {
            new Pattern('€ (?i)+');
        } catch (SyntaxException $exception) {
            $this->assertSame(6, $exception->syntaxErrorOffset());
        }
    }

    /**
     * @test
     */
    public function malformedUnicode()
    {
        try {
            new Pattern("\xe2\x28\xa1");
        } catch (SyntaxException $exception) {
            catching(fn() => $exception->syntaxErrorOffset())
                ->assertException(UnicodeException::class)
                ->assertMessage('Byte offset 3 does not point to a valid unicode code point.');
        }
    }
}

<?php
namespace Test\Unit;

use PHPUnit\Framework\TestCase;
use Regex\Pattern;
use Regex\SyntaxException;
use Regex\UnicodeException;
use function Test\Fixture\Functions\catching;
use function Test\Fixture\Functions\since;

class _syntaxErrorPosition extends TestCase
{
    public function test()
    {
        catching(fn() => new Pattern('\w+\d+++'))
            ->assertException(SyntaxException::class)
            ->assertMessage('Quantifier does not follow a repeatable item, near position 7.');
    }

    /**
     * @test
     */
    public function trailingBackslash()
    {
        catching(fn() => new Pattern('[a-z0-9]\\'))
            ->assertException(SyntaxException::class)
            ->assertMessage('Trailing backslash in regular expression, near position 8.');
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
            $call->assertExceptionNone();
        } else {
            $call
                ->assertException(SyntaxException::class)
                ->assertMessage('Null byte in regex, near position 4.');
        }
    }

    /**
     * @test
     */
    public function unicode()
    {
        catching(fn() => new Pattern("[a-z] \xe2\x28\xa1", 'u'))
            ->assertException(UnicodeException::class)
            ->assertMessageStartsWith('Malformed regular expression, byte 2 top bits not 0x80, near position 6.');
    }
}

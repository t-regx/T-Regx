<?php
namespace Test\Unit;

use PHPUnit\Framework\TestCase;
use Regex\Pattern;
use Regex\SyntaxException;
use Regex\UnicodeException;
use function Test\Fixture\Functions\catching;

class _unicode extends TestCase
{
    /**
     * @test
     */
    public function unicodeModifier()
    {
        catching(fn() => new Pattern("[a-z] \xe2\x28\xa1", 'u'))
            ->assertException(UnicodeException::class)
            ->assertMessage('Malformed regular expression, byte 2 top bits not 0x80 at offset 6.');
    }

    /**
     * @test
     */
    public function unicodeOption()
    {
        catching(fn() => new Pattern("(*UTF)[a-z] \xe2\x28\xa1"))
            ->assertException(UnicodeException::class)
            ->assertMessage('Malformed regular expression, byte 2 top bits not 0x80 at offset 12.');
    }

    /**
     * @test
     */
    public function ascii()
    {
        catching(fn() => new Pattern("[a-z] \xe2\x28\xa1"))
            ->assertException(SyntaxException::class)
            ->assertMessage('Missing closing parenthesis at offset 9.');
    }
}

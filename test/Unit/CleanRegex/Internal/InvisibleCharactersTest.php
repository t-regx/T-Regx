<?php
namespace Test\Unit\TRegx\CleanRegex\Internal;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\InvisibleCharacters;

class InvisibleCharactersTest extends TestCase
{
    /**
     * @test
     * @dataProvider characters
     * @param string $value
     * @param string $expected
     */
    public function test(string $value, string $expected)
    {
        // when
        $visible = InvisibleCharacters::format($value);

        // then
        $this->assertSame($expected, $visible);
    }

    public function characters(): array
    {
        return [
            ['', ''],
            ["\0", '\x0'],
            ["\1", '\x1'],
            ["\2", '\x2'],
            ["\x8", '\b'], # backspace
            ["\t", '\t'], # horizontal tab
            ["\t", '\t'], # horizontal tab
            ["\n", '\n'], # line feed
            ["\r", '\r'], # carriage return
            ["\v", '\v'], # vertical tab
            ["\e", '\e'], # escape
            ["\f", '\f'], # form feed
            ["\x7f", '[DEL\x7f]'], # delete
            ["\xc2\xa0", '[NBSP\xc2\xa0]'], # nbsp

            ['Foo\ bar', 'Foo\ bar'],
            ["Foo\n bar", 'Foo\n bar'],

            ['śćź', 'śćź'],
            ['!@#$%', '!@#$%'],
        ];
    }
}

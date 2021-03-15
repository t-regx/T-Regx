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
            ["\x7f", '\x7f'], # delete
            ["\xc2\xa0", '\xc2\xa0'], # nbsp
            ["\xc2\xa6", '¦'],
            ["\xd8\x81", '\xd8\x81'],

            ['Foo\ bar', 'Foo\ bar'],
            ["Foo\n bar", 'Foo\n bar'],

            ['śćź', 'śćź'],
            ['!@#$%', '!@#$%'],
            ['Foo€', 'Foo€'],

            [
                "Hello, śćź !@#$%^~ \x1f \x7f \xee", # Malformed UTF8
                'Hello, \xc5\x9b\xc4\x87\xc5\xba !@#$%^~ \x1f \x7f \xee'
            ],
        ];
    }
}

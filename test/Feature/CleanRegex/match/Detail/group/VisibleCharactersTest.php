<?php
namespace Test\Feature\CleanRegex\match\Detail\group;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Test\Utils\TestCase\TestCaseExactMessage;
use TRegx\CleanRegex\Pattern;

/**
 * @covers \TRegx\CleanRegex\Internal\VisibleCharacters
 */
class VisibleCharactersTest extends TestCase
{
    use TestCaseExactMessage;

    /**
     * @test
     * @dataProvider characters
     * @param string $value
     * @param string $expected
     */
    public function test(string $value, string $expected)
    {
        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Group name must be an alphanumeric string, not starting with a digit, but '$expected' given");
        // when
        Pattern::of('Foo')->match('Foo')->first()->group($value);
    }

    public function characters(): array
    {
        return \named([
            ['', ''],
            ["\0", '\x0'],
            ["\1", '\x1'],
            ["\2", '\x2'],
            ["\x8", '\b'], # backspace
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
                "\x1f \x7f \xee", # Malformed UTF8
                '\x1f \x7f \xee'
            ],
        ]);
    }
}

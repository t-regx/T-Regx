<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Prepared\Quotable;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\Prepared\Quotable\UserInputQuotable;

class UserInputQuotableTest extends TestCase
{
    /**
     * @test
     * @dataProvider extended
     * @param string $input
     * @param string $expected
     */
    public function shouldQuoteExtended(string $input, string $expected)
    {
        // given
        $quotable = new UserInputQuotable($input);

        // when
        $result = $quotable->quote('/');

        // then
        $this->assertSame($expected, $result);
    }

    public function extended(): array
    {
        return [
            'empty'     => ['', ''],
            'slash'     => ['/', '\\/'],
            'backslash' => ['\\', '\\\\'],
            'hash'      => ['Foo#Bar', 'Foo\#Bar'],

            'space' => ['Foo Bar', 'Foo\ Bar'],
            '\n'    => ["Foo\nBar", "Foo\\\nBar"],
            '\r'    => ["Foo\rBar", "Foo\\\rBar"],
            '\t'    => ["Foo\tBar", "Foo\\\tBar"],
            '\f'    => ["Foo\fBar", "Foo\\\fBar"],
            '\x0B'  => ["Foo\x0BBar", "Foo\\\x0BBar"],
        ];
    }
}

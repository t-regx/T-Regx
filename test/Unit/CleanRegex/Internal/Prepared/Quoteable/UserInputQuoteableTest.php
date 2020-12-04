<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Prepared\Quoteable;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\Prepared\Quoteable\UserInputQuoteable;

class UserInputQuoteableTest extends TestCase
{
    /**
     * @test
     * @dataProvider extended
     */
    public function shouldQuoteExtended(string $input, string $expected)
    {
        // given
        $quotable = new UserInputQuoteable($input);

        // when
        $result = $quotable->quote('/');

        // then
        $this->assertEqual($expected, $result);
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

    public function assertEqual(string $expected, string $actual): void
    {
        $this->assertSame($expected, $actual, "Their byte representation: " . "\n"
            . 'Expected: ' . $this->bytes($expected) . "\n"
            . 'Actual:   ' . $this->bytes($actual)
        );
    }

    private function bytes(string $expected): string
    {
        return join(", ", array_map('ord', str_split($expected)));
    }
}

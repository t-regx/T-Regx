<?php
namespace Test\Feature\CleanRegex\_prepared\_whitespace;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Pattern;

class Test extends TestCase
{
    public function extended(): array
    {
        return [
            'empty'     => ['', ''],
            'slash'     => ['/', '\/'],
            'backslash' => ['\\', '\\\\'],
            'hash'      => ['Foo#Bar', 'Foo\#Bar'],
            'space'     => ['Foo Bar', 'Foo\ Bar'],
            '\n'        => ["Foo\nBar", 'Foo\nBar'],
            '\r'        => ["Foo\rBar", 'Foo\rBar'],
            '\t'        => ["Foo\tBar", 'Foo\tBar'],
            '\f'        => ["Foo\fBar", 'Foo\fBar'],
            '\v'        => ["Foo\vBar", 'Foo\vBar'],
        ];
    }

    /**
     * @test
     * @dataProvider extended
     * @param string $text
     * @param string $expected
     */
    public function shouldEscapeExtendedLiteral(string $text, string $expected)
    {
        // when
        $pattern = Pattern::literal($text);
        // then
        $this->assertSame("/$expected/", $pattern->delimited());
    }

    /**
     * @test
     * @dataProvider extended
     * @param string $text
     * @param string $expected
     */
    public function shouldEscapeExtendedAlteration(string $text, string $expected)
    {
        // when
        $pattern = Pattern::alteration([$text]);
        // then
        $this->assertSame("/$expected/", $pattern->delimited());
    }

    /**
     * @test
     * @dataProvider extended
     * @param string $text
     * @param string $expected
     */
    public function shouldEscapeExtendedInject(string $text, string $expected)
    {
        // when
        $pattern = Pattern::inject('@', [$text]);
        // then
        $this->assertSame("/(?>$expected)/", $pattern->delimited());
    }
}

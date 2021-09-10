<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Prepared\Word;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\Prepared\Word\TextWord;

/**
 * @covers \TRegx\CleanRegex\Internal\Prepared\Word\TextWord
 */
class TextWordTest extends TestCase
{
    /**
     * @test
     * @dataProvider extended
     * @param string $text
     * @param string $expected
     */
    public function shouldQuoteExtended(string $text, string $expected)
    {
        // given
        $word = new TextWord($text);

        // when
        $result = $word->quoted('/');

        // then
        $this->assertSame($expected, $result);
    }

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
     */
    public function shouldThrowForInvalidDelimiter()
    {
        // given
        $word = new TextWord('welcome');

        // then
        $this->expectException(InvalidArgumentException::class);

        // when
        $word->quoted('foo');
    }
}

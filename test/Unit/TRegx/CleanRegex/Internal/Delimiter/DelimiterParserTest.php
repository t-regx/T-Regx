<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Delimiter;

use TRegx\CleanRegex\Internal\Delimiter\DelimiterParser;
use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\FlagsValidator;

class DelimiterParserTest extends TestCase
{
    public function delimitered()
    {
        return [
            ['//', '/'],
            ['/a/', '/'],
            ['/word/', '/'],
            ['//im', '/'],
            ['/word/im', '/'],

            ['/sie#ma/', '/'],
            ['#sie/ma#', '#'],
            ['%si/e#ma%', '%'],
            ['~si/e#m%a~', '~'],
            ['+s~i/e#m%a+', '+'],
            ['!s~i/e#++m%a!', '!'],
            ['@!s~i/e#++m%a!@', '@'],
        ];
    }

    /**
     * @test
     * @dataProvider delimitered
     * @param string $pattern
     * @param string $delimiter
     */
    public function shouldGetDelimiter(string $pattern, string $delimiter)
    {
        // given
        $delimiterer = new DelimiterParser(new FlagsValidator());

        // when
        $result = $delimiterer->getDelimiter($pattern);

        // then
        $this->assertEquals($delimiter, $result, "Failed asserting that '$result' is a delimiter of '$pattern'");
    }

    /**
     * @test
     * @dataProvider delimitered
     * @param string $pattern
     */
    public function shouldBeDelimitered(string $pattern)
    {
        // given
        $delimiterer = new DelimiterParser(new FlagsValidator());

        // when
        $result = $delimiterer->isDelimitered($pattern);

        // then
        $this->assertTrue($result);
    }

    public function notDelimitered()
    {
        return [
            [''],
            ['a'],
            ['/'],
            ['///'],
            ['//12'],
            ['//abc'],

            ['word'],
            ['sie#ma'],
            ['si/e#ma'],
            ['s~i/e#m%a'],
            ['s~i/e#++m%a'],

            ['/word'],
            ['/word/2'],
            ['/word/word/'],
            ['/sie#ma'],
            ['#sie/ma'],
            ['%si/e#ma'],
            ['si/e#m%a~'],
            ['s~i/e#m%a+'],
            ['s~i/e#++m%a!'],
        ];
    }

    /**
     * @test
     * @dataProvider notDelimitered
     * @param string $pattern
     */
    public function shouldNotGetDelimiter(string $pattern)
    {
        // given
        $delimiterer = new DelimiterParser(new FlagsValidator());

        // when
        $result = $delimiterer->getDelimiter($pattern);

        // then
        $this->assertNull($result, "Failed asserting that '$pattern' does not have a delimiter");
    }

    /**
     * @test
     * @dataProvider notDelimitered
     * @param string $pattern
     */
    public function shouldNotBeDelimitered(string $pattern)
    {
        // given
        $delimiterer = new DelimiterParser(new FlagsValidator());

        // when
        $result = $delimiterer->isDelimitered($pattern);

        // then
        $this->assertFalse($result, "Failed asserting that '$pattern' is not delimiter");
    }
}

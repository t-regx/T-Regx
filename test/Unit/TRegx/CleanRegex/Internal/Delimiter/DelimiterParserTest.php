<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Delimiter;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\Delimiter\DelimiterParser;

class DelimiterParserTest extends TestCase
{
    public function delimitered()
    {
        return [
            ['//', '/'],
            ['/a/', '/'],
            ['/siema/', '/'],
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
        $delimiterer = new DelimiterParser();

        // when
        $result = $delimiterer->getDelimiter($pattern);

        // then
        $this->assertEquals($delimiter, $result);
    }

    public function notDelimitered()
    {
        return [
            [''],
            ['a'],
            ['/'],
            ['siema'],
            ['sie#ma'],
            ['si/e#ma'],
            ['s~i/e#m%a'],
            ['s~i/e#++m%a'],

            ['/siema'],
            ['/sie#ma'],
            ['#sie/ma'],
            ['%si/e#ma'],
            ['si/e#m%a~'],
            ['s~i/e#m%a+'],
            ['s~i/e#++m%a!'],

            ['(s~i/e#++m%a!)'],
            ['[s~i/e#++m%a!]'],
            ['{s~i/e#++m%a!}'],
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
        $delimiterer = new DelimiterParser();

        // when
        $result = $delimiterer->getDelimiter($pattern);

        // then
        $this->assertNull($result);
    }

    /**
     * @test
     * @dataProvider notDelimitered
     * @param string $pattern
     */
    public function shouldNotBeDelimitered(string $pattern)
    {
        // given
        $delimiterer = new DelimiterParser();

        // when
        $result = $delimiterer->isDelimitered($pattern);

        // then
        $this->assertFalse($result);
    }

    /**
     * @test
     * @dataProvider delimitered
     * @param string $pattern
     */
    public function shouldBeDelimitered(string $pattern)
    {
        // given
        $delimiterer = new DelimiterParser();

        // when
        $result = $delimiterer->isDelimitered($pattern);

        // then
        $this->assertTrue($result);
    }
}

<?php
namespace CleanRegex\Internal;

use PHPUnit\Framework\TestCase;

class PatternDelimitererTest extends TestCase
{
    public function patternsAndResults()
    {
        return [
            ['siema', '/siema/'],
            ['sie#ma', '/sie#ma/'],
            ['sie/ma', '#sie/ma#'],
            ['si/e#ma', '%si/e#ma%'],
            ['si/e#m%a', '~si/e#m%a~'],
            ['s~i/e#m%a', '+s~i/e#m%a+'],
            ['s~i/e#++m%a', '!s~i/e#++m%a!'],
        ];
    }

    /**
     * @test
     * @dataProvider patternsAndResults
     * @param string $pattern
     * @param string $expectedResult
     */
    public function shouldDelimiterPattern(string $pattern, string $expectedResult)
    {
        // given
        $delimiterer = new PatternDelimiterer();

        // when
        $result = $delimiterer->delimiter($pattern);

        // then
        $this->assertEquals($expectedResult, $result);
    }

    /**
     * @test
     * @dataProvider patternsAndResults
     * @param string $pattern
     * @param string $expectedResult
     */
    public function shouldNotBeDelimitered(string $pattern, string $expectedResult)
    {
        // given
        $delimiterer = new PatternDelimiterer();

        // when
        $result = $delimiterer->isDelimitered($pattern);

        // then
        $this->assertFalse($result);
    }

    public function alreadyDelimitered()
    {
        return [
            ['/a/'],
            ['#a#'],
            ['%a%'],
            ['~a~'],
            ['+a+'],
            ['!a!'],
        ];
    }

    /**
     * @test
     * @dataProvider alreadyDelimitered
     * @param string $pattern
     */
    public function shouldDelimiterAlreadyDelimitered(string $pattern)
    {
        // given
        $delimiterer = new PatternDelimiterer();

        // when
        $result = $delimiterer->delimiter($pattern);

        // then
        $this->assertEquals($pattern, $result);
    }

    /**
     * @test
     * @dataProvider alreadyDelimitered
     * @param string $pattern
     */
    public function shouldBeDelimitered(string $pattern)
    {
        // given
        $delimiterer = new PatternDelimiterer();

        // when
        $result = $delimiterer->isDelimitered($pattern);

        // then
        $this->assertTrue($result);
    }

    /**
     * @test
     */
    public function shouldThrowOnNotEnoughDelimiters()
    {
        // given
        $delimiterer = new PatternDelimiterer();

        // then
        $this->expectException(ExplicitDelimiterRequiredException::class);

        // when
        $delimiterer->delimiter('s~i/e#++m%a!');
    }
}

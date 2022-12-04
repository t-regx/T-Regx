<?php
namespace Test\Feature\CleanRegex\valid;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\PcrePattern;
use TRegx\Exception\MalformedPatternException;

class PcrePatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldPcrePatternBeValid()
    {
        // given
        $pattern = PcrePattern::of('/Foo/');
        // when, then
        $this->assertTrue($pattern->valid());
    }

    /**
     * @test
     */
    public function shouldMalformedPatternNotBeValid()
    {
        // given
        $pattern = PcrePattern::of('/invalid)/');
        // when, then
        $this->assertFalse($pattern->valid());
    }

    /**
     * @test
     */
    public function shouldThrowForUndelimitedPattern()
    {
        // then
        $this->expectException(MalformedPatternException::class);
        $this->expectExceptionMessage('Delimiter must not be alphanumeric or backslash');
        // when
        PcrePattern::of('Foo')->test('foo');
    }

    /**
     * @test
     */
    public function shouldThrowForUndelimitedPatternMalformed()
    {
        // then
        $this->expectException(MalformedPatternException::class);
        $this->expectExceptionMessage('Delimiter must not be alphanumeric or backslash');
        // when
        PcrePattern::of('invalid)')->test('foo');
    }

    /**
     * @test
     */
    public function shouldThrowForEmptyPattern()
    {
        // then
        $this->expectException(MalformedPatternException::class);
        $this->expectExceptionMessage('Empty regular expression');
        // when
        PcrePattern::of('')->test('foo');
    }
}

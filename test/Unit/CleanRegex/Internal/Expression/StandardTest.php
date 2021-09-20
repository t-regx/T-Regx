<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Expression;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Exception\PatternMalformedPatternException;
use TRegx\CleanRegex\Internal\Definition;
use TRegx\CleanRegex\Internal\Expression\Standard;

/**
 * @covers \TRegx\CleanRegex\Internal\Expression\Standard
 */
class StandardTest extends TestCase
{
    /**
     * @test
     */
    public function test()
    {
        // given
        $standard = new Standard('foo', 'i');

        // when
        $predefinition = $standard->predefinition();

        // then
        $this->assertEquals(new Definition('/foo/i', 'foo'), $predefinition->definition());
    }

    /**
     * @test
     */
    public function shouldChooseDelimiter()
    {
        // given
        $standard = new Standard('foo/bar', 'x');

        // when
        $predefinition = $standard->predefinition();

        // then
        $this->assertEquals(new Definition('#foo/bar#x', 'foo/bar'), $predefinition->definition());
    }

    /**
     * @test
     */
    public function shouldThrowForTrailingEscape()
    {
        // given
        $standard = new Standard('bar\\', 'x');

        // then
        $this->expectException(PatternMalformedPatternException::class);
        $this->expectExceptionMessage('Pattern may not end with a trailing backslash');

        // when
        $standard->predefinition();
    }

    /**
     * @test
     */
    public function shouldNotUseDuplicateFlags()
    {
        // given
        $standard = new Standard('foo', 'mm');

        // when
        $predefinition = $standard->predefinition();

        // then
        $this->assertEquals(new Definition('/foo/m', 'foo'), $predefinition->definition());
    }
}

<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Prepared\Expression;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Exception\MaskMalformedPatternException;
use TRegx\CleanRegex\Internal\Definition;
use TRegx\CleanRegex\Internal\Prepared\Expression\Mask;

/**
 * @covers \TRegx\CleanRegex\Internal\Prepared\Expression\Mask
 */
class MaskTest extends TestCase
{
    /**
     * @test
     */
    public function test()
    {
        // given
        $mask = new Mask('(%w:%s\)', [
            '%s' => '\s',
            '%w' => '\w'
        ], 'x');

        // when
        $predefinition = $mask->predefinition();

        // then
        $this->assertEquals(new Definition('/\(\w\:\s\\\\\\)/x', '(%w:%s\)'), $predefinition->definition());
    }

    /**
     * @test
     */
    public function shouldChooseDelimiter()
    {
        // given
        $mask = new Mask('foo', ['x' => '%', '%w' => '#', '%s' => '/'], 'i');

        // when
        $predefinition = $mask->predefinition();

        // then
        $this->assertEquals(new Definition('~foo~i', 'foo'), $predefinition->definition());
    }

    /**
     * @test
     */
    public function shouldNotUseMaskToDelimiter()
    {
        // given
        $mask = new Mask('foo/bar', [], 'x');

        // when
        $predefinition = $mask->predefinition();

        // then
        $this->assertEquals(new Definition('/foo\/bar/x', 'foo/bar'), $predefinition->definition());
    }

    /**
     * @test
     */
    public function shouldThrowForTrailingEscape()
    {
        // given
        $mask = new Mask('(%w:%s\)', [
            '%s' => '\s',
            '%w' => '\w\\'
        ], 'x');

        // then
        $this->expectException(MaskMalformedPatternException::class);
        $this->expectExceptionMessage("Malformed pattern '\w\' assigned to keyword '%w'");

        // when
        $mask->predefinition();
    }

    /**
     * @test
     */
    public function shouldNotUseDuplicateFlags()
    {
        // given
        $mask = new Mask('foo', [], 'xx');

        // when
        $predefinition = $mask->predefinition();

        // then
        $this->assertEquals(new Definition('/foo/x', 'foo'), $predefinition->definition());
    }
}

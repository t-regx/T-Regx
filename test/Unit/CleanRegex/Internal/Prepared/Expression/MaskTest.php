<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Prepared\Expression;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Exception\MaskMalformedPatternException;
use TRegx\CleanRegex\Internal\Definition;
use TRegx\CleanRegex\Internal\Flags;
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
        ], new Flags('x'));

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
        $mask = new Mask('foo', ['x' => '%', '%w' => '#', '%s' => '/'], new Flags('i'));

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
        $mask = new Mask('foo/bar', [], new Flags('x'));

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
        ], new Flags('x'));

        // then
        $this->expectException(MaskMalformedPatternException::class);
        $this->expectExceptionMessage("Malformed pattern '\w\' assigned to keyword '%w'");

        // when
        $mask->predefinition();
    }
}

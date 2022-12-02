<?php
namespace Test\Unit\CleanRegex\Internal\Prepared\Expression;

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
        $mask = new Mask('(%w:%s\)', new Flags('x'), [
            '%s' => '\s',
            '%w' => '\w'
        ]);

        // when
        $predefinition = $mask->predefinition();

        // then
        $this->assertEquals(new Definition('/\(\w\:\s\\\\\\)/x'), $predefinition->definition());
    }

    /**
     * @test
     */
    public function shouldChooseDelimiter()
    {
        // given
        $mask = new Mask('foo', new Flags('i'), ['x' => '%', '%w' => '#', '%s' => '/']);

        // when
        $predefinition = $mask->predefinition();

        // then
        $this->assertEquals(new Definition('~foo~i'), $predefinition->definition());
    }

    /**
     * @test
     */
    public function shouldNotUseMaskToDelimiter()
    {
        // given
        $mask = new Mask('foo/bar', new Flags('x'), []);

        // when
        $predefinition = $mask->predefinition();

        // then
        $this->assertEquals(new Definition('/foo\/bar/x'), $predefinition->definition());
    }

    /**
     * @test
     */
    public function shouldThrowForTrailingEscape()
    {
        // given
        $mask = new Mask('(%w:%s\)', new Flags('x'), [
            '%s' => '\s',
            '%w' => '\w\\'
        ]);

        // then
        $this->expectException(MaskMalformedPatternException::class);
        $this->expectExceptionMessage("Malformed pattern '\w\' assigned to keyword '%w'");

        // when
        $mask->predefinition();
    }
}

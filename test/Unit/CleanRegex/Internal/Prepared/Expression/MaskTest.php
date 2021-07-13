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
        $interpretation = new Mask('(%w:%s\)', [
            '%s' => '\s',
            '%w' => '\w'
        ], 'x');

        // when
        $definition = $interpretation->definition();

        // then
        $this->assertEquals(new Definition('/\(\w\:\s\\\\\\)/x', '(%w:%s\)'), $definition);
    }

    /**
     * @test
     */
    public function shouldChooseDelimiter()
    {
        // given
        $interpretation = new Mask('foo', ['x' => '%', '%w' => '#', '%s' => '/'], 'i');

        // when
        $definition = $interpretation->definition();

        // then
        $this->assertEquals(new Definition('~foo~i', 'foo'), $definition);
    }

    /**
     * @test
     */
    public function shouldNotUseMaskToDelimiter()
    {
        // given
        $interpretation = new Mask('foo/bar', [], 'x');

        // when
        $definition = $interpretation->definition();

        // then
        $this->assertEquals(new Definition('/foo\/bar/x', 'foo/bar'), $definition);
    }

    /**
     * @test
     */
    public function shouldThrowForTrailingEscape()
    {
        // given
        $interpretation = new Mask('(%w:%s\)', [
            '%s' => '\s',
            '%w' => '\w\\'
        ], 'x');

        // then
        $this->expectException(MaskMalformedPatternException::class);
        $this->expectExceptionMessage("Malformed pattern '\w\' assigned to keyword '%w'");

        // when
        $interpretation->definition();
    }

    /**
     * @test
     */
    public function shouldNotUseDuplicateFlags()
    {
        // given
        $interpretation = new Mask('foo', [], 'xx');

        // when
        $definition = $interpretation->definition();

        // then
        $this->assertEquals(new Definition('/foo/x', 'foo'), $definition);
    }
}

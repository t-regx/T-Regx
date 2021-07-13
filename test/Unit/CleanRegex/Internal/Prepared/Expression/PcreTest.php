<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Prepared\Expression;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\Definition;
use TRegx\CleanRegex\Internal\Prepared\Expression\Pcre;

/**
 * @covers \TRegx\CleanRegex\Internal\Prepared\Expression\Pcre
 */
class PcreTest extends TestCase
{
    /**
     * @test
     */
    public function test()
    {
        // given
        $interpretation = new Pcre('/foo/x');

        // when
        $actual = $interpretation->definition();

        // then
        $this->assertEquals(new Definition('/foo/x', '/foo/x'), $actual);
    }
}

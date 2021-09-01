<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Prepared\Expression;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\Definition;
use TRegx\CleanRegex\Internal\Expression\Pcre;

/**
 * @covers \TRegx\CleanRegex\Internal\Expression\Pcre
 */
class PcreTest extends TestCase
{
    /**
     * @test
     */
    public function test()
    {
        // given
        $pcre = new Pcre('/foo/x');

        // when
        $actual = $pcre->definition();

        // then
        $this->assertEquals(new Definition('/foo/x', '/foo/x'), $actual);
    }
}

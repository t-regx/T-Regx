<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Expression;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\Definition;
use TRegx\CleanRegex\Internal\Expression\Pcre;
use TRegx\CleanRegex\Internal\Expression\Predefinition\IdentityPredefinition;

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
        $actual = $pcre->predefinition();

        // then
        $this->assertEquals(new IdentityPredefinition(new Definition('/foo/x', '/foo/x')), $actual);
    }
}

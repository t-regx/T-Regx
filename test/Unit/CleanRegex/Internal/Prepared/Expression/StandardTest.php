<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Prepared\Expression;

use PHPUnit\Framework\TestCase;
use Test\Fakes\CleanRegex\Internal\EqualsCondition;
use TRegx\CleanRegex\Internal\Definition;
use TRegx\CleanRegex\Internal\Prepared\Expression\Standard;
use TRegx\CleanRegex\Internal\Prepared\Orthography\StandardSpelling;

/**
 * @covers \TRegx\CleanRegex\Internal\Prepared\Expression\Standard
 */
class StandardTest extends TestCase
{
    /**
     * @test
     */
    public function test()
    {
        // given
        $standard = new Standard(new StandardSpelling('foo', 'i', new EqualsCondition('/')));

        // when
        $predefinition = $standard->predefinition();

        // then
        $this->assertEquals(new Definition('/foo/i', 'foo'), $predefinition->definition());
    }

    /**
     * @test
     */
    public function shouldNotUseDuplicateFlags()
    {
        // given
        $standard = new Standard(new StandardSpelling('foo', 'mm', new EqualsCondition('/')));

        // when
        $predefinition = $standard->predefinition();

        // then
        $this->assertEquals(new Definition('/foo/m', 'foo'), $predefinition->definition());
    }
}

<?php
namespace Test\Unit\CleanRegex\Internal\Prepared\Expression;

use PHPUnit\Framework\TestCase;
use Test\Fakes\CleanRegex\Internal\EqualsCondition;
use TRegx\CleanRegex\Internal\Definition;
use TRegx\CleanRegex\Internal\Flags;
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
        $standard = new Standard(new StandardSpelling('foo', new Flags('i'), new EqualsCondition('/')));

        // when
        $predefinition = $standard->predefinition();

        // then
        $this->assertEquals(new Definition('/foo/i', 'foo'), $predefinition->definition());
    }
}

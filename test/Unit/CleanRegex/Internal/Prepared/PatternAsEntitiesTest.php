<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Prepared;

use PHPUnit\Framework\TestCase;
use Test\Fakes\CleanRegex\Internal\Prepared\Parser\Consumer\ThrowPlaceholderConsumer;
use TRegx\CleanRegex\Internal\Flags;
use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\GroupClose;
use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\GroupOpen;
use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\Literal;
use TRegx\CleanRegex\Internal\Prepared\PatternAsEntities;

/**
 * @covers \TRegx\CleanRegex\Internal\Prepared\PatternAsEntities
 */
class PatternAsEntitiesTest extends TestCase
{
    /**
     * @test
     */
    public function shouldCloseGroup()
    {
        // given
        $asEntities = new PatternAsEntities('(foo)', new Flags(''), new ThrowPlaceholderConsumer());

        // when
        $entities = $asEntities->entities();

        // then
        $expected = [
            new GroupOpen(),
            new Literal('f'),
            new Literal('o'),
            new Literal('o'),
            new GroupClose(),
        ];
        $this->assertEquals($expected, $entities);
    }
}

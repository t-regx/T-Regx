<?php
namespace Test\Unit\CleanRegex\Internal\Prepared\Parser\Consumer;

use PHPUnit\Framework\TestCase;
use Test\Utils\Prepared\PatternEntitiesAssertion;
use TRegx\CleanRegex\Internal\Prepared\Parser\Consumer\GroupCloseConsumer;
use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\GroupClose;

/**
 * @covers \TRegx\CleanRegex\Internal\Prepared\Parser\Consumer\GroupCloseConsumer
 */
class GroupCloseConsumerTest extends TestCase
{
    /**
     * @test
     */
    public function test()
    {
        // given
        $assertion = PatternEntitiesAssertion::withConsumers([new GroupCloseConsumer()]);

        // then
        $assertion->assertPatternRepresents(')', [new GroupClose()]);
    }
}

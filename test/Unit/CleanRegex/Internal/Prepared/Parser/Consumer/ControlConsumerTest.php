<?php
namespace Test\Unit\CleanRegex\Internal\Prepared\Parser\Consumer;

use PHPUnit\Framework\TestCase;
use Test\Utils\EntityFailAssertion;
use Test\Utils\PatternEntitiesAssertion;
use TRegx\CleanRegex\Internal\Prepared\Parser\Consumer\ControlConsumer;
use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\Control;

/**
 * @covers \TRegx\CleanRegex\Internal\Prepared\Parser\Consumer\ControlConsumer
 */
class ControlConsumerTest extends TestCase
{
    /**
     * @test
     */
    public function shouldFailToParseEscape()
    {
        // given
        $assertion = new EntityFailAssertion($this, [new ControlConsumer()]);

        // then
        $assertion->assertPatternFails('\\');
    }

    /**
     * @test
     */
    public function shouldFailToParseUnrelated()
    {
        // given
        $assertion = new EntityFailAssertion($this, [new ControlConsumer()]);

        // then
        $assertion->assertPatternFails('\d');
    }

    /**
     * @test
     */
    public function shouldParseControl()
    {
        // given
        $assertion = PatternEntitiesAssertion::withConsumers([new ControlConsumer()]);

        // then
        $assertion->assertPatternRepresents('\cx', [new Control('x')]);
    }

    /**
     * @test
     */
    public function shouldParseEmptyControl()
    {
        // given
        $assertion = PatternEntitiesAssertion::withConsumers([new ControlConsumer()]);

        // then
        $assertion->assertPatternRepresents('\c', [new Control('')]);
    }
}

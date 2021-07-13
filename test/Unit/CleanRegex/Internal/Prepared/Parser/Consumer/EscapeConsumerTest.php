<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Prepared\Parser\Consumer;

use PHPUnit\Framework\TestCase;
use Test\Utils\PatternEntitiesAssertion;
use TRegx\CleanRegex\Internal\Prepared\Parser\Consumer\EscapeConsumer;
use TRegx\CleanRegex\Internal\Prepared\Parser\Consumer\LiteralConsumer;
use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\Escaped;
use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\TerminatingEscape;

/**
 * @covers \TRegx\CleanRegex\Internal\Prepared\Parser\Consumer\EscapeConsumer
 */
class EscapeConsumerTest extends TestCase
{
    /**
     * @test
     */
    public function shouldConsumeEscaped()
    {
        // given
        $assertion = PatternEntitiesAssertion::withConsumers([new EscapeConsumer(), new LiteralConsumer()]);

        // then
        $assertion->assertPatternRepresents('\n\foo', [new Escaped('n'), new Escaped('f'), 'oo']);
    }

    /**
     * @test
     */
    public function shouldConsumeEscapedNewline()
    {
        // given
        $assertion = PatternEntitiesAssertion::withConsumers([new EscapeConsumer()]);

        // then
        $assertion->assertPatternRepresents("\\\n", [new Escaped("\n")]);
    }

    /**
     * @test
     */
    public function shouldConsumeEscapedEscape()
    {
        // given
        $assertion = PatternEntitiesAssertion::withConsumers([new EscapeConsumer(), new LiteralConsumer()]);

        // then
        $assertion->assertPatternRepresents('\\\\x', [new Escaped('\\'), 'x']);
    }

    /**
     * @test
     */
    public function shouldConsumeTerminatingEscape()
    {
        // given
        $assertion = PatternEntitiesAssertion::withConsumers([new EscapeConsumer()]);

        // then
        $assertion->assertPatternRepresents('\\', [new TerminatingEscape()]);
    }

    /**
     * @test
     */
    public function shouldConsumeThreeEscapes()
    {
        // given
        $assertion = PatternEntitiesAssertion::withConsumers([new EscapeConsumer()]);

        // then
        $assertion->assertPatternRepresents('\\\\\\', [new Escaped('\\'), new TerminatingEscape()]);
    }

    /**
     * @test
     */
    public function shouldConsumeEscapedValuePlaceholder()
    {
        // given
        $assertion = PatternEntitiesAssertion::withConsumers([new EscapeConsumer()]);

        // then
        $assertion->assertPatternRepresents('\@', [new Escaped('@')]);
    }

    /**
     * @test
     */
    public function shouldConsumeEscapedPatternPlaceholder()
    {
        // given
        $assertion = PatternEntitiesAssertion::withConsumers([new EscapeConsumer()]);

        // then
        $assertion->assertPatternRepresents('\&', [new Escaped('&')]);
    }
}

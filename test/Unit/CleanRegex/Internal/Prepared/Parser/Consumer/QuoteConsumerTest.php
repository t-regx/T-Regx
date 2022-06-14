<?php
namespace Test\Unit\CleanRegex\Internal\Prepared\Parser\Consumer;

use PHPUnit\Framework\TestCase;
use Test\Utils\Prepared\EntityFailAssertion;
use Test\Utils\Prepared\PatternEntitiesAssertion;
use TRegx\CleanRegex\Internal\Prepared\Parser\Consumer\EscapeConsumer;
use TRegx\CleanRegex\Internal\Prepared\Parser\Consumer\LiteralConsumer;
use TRegx\CleanRegex\Internal\Prepared\Parser\Consumer\QuoteConsumer;
use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\Escaped;
use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\Quote;

/**
 * @covers \TRegx\CleanRegex\Internal\Prepared\Parser\Consumer\QuoteConsumer
 */
class QuoteConsumerTest extends TestCase
{
    /**
     * @test
     * @dataProvider quotes
     */
    public function test(string $pattern, array $expected)
    {
        // given
        $assertion = PatternEntitiesAssertion::withConsumers([
            new QuoteConsumer(),
            new EscapeConsumer(),
            new LiteralConsumer()
        ]);

        // then
        $assertion->assertPatternRepresents($pattern, $expected);
    }

    public function quotes(): array
    {
        return [
            ['\Q', [new Quote('', false)]],
            ['\Q\E', [new Quote('', true)]],
            ['\Qfoo\E', [new Quote('foo', true)]],
            ['\Qfoo', [new Quote('foo', false)]],
            ['\Q@\E', [new Quote('@', true)]],
            ['\Qx\\\E', [new Quote('x\\', true)]],
            ['\Qx\\\\\E', [new Quote('x\\\\', true)]],
            ['\Q\Q@\E', [new Quote('\Q@', true)]],
            ['\Q@\E\E', [new Quote('@', true), new Escaped('E')]],
            ['\Q{@}(hi)[hey]\E', [new Quote('{@}(hi)[hey]', true)]],
            ['\Q:foo(bar)\x', [new Quote(':foo(bar)\x', false)]],
            ["\Q:foo(\n)bar\E", [new Quote(":foo(\n)bar", true)]],
        ];
    }

    /**
     * @test
     */
    public function shouldNotConsumeClosingQuote()
    {
        // given
        $assertion = new EntityFailAssertion($this, [new QuoteConsumer()]);

        // then
        $assertion->assertPatternFails('\E');
    }
}

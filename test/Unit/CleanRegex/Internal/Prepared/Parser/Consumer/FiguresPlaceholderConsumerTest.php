<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Prepared\Parser\Consumer;

use PHPUnit\Framework\TestCase;
use Test\Utils\Impl\ConstantFigures;
use Test\Utils\PatternEntitiesAssertion;
use TRegx\CleanRegex\Internal\Flags;
use TRegx\CleanRegex\Internal\Prepared\Figure\ExpectedFigures;
use TRegx\CleanRegex\Internal\Prepared\Parser\Consumer\FiguresPlaceholderConsumer;
use TRegx\CleanRegex\Internal\Prepared\Parser\Consumer\GroupConsumer;
use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\GroupOpenFlags;
use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\Placeholder;
use TRegx\CleanRegex\Internal\Prepared\Template\LiteralToken;

/**
 * @covers \TRegx\CleanRegex\Internal\Prepared\Parser\Consumer\FiguresPlaceholderConsumer
 */
class FiguresPlaceholderConsumerTest extends TestCase
{
    /**
     * @test
     */
    public function test()
    {
        // given
        $assertion = PatternEntitiesAssertion::withConsumers([new FiguresPlaceholderConsumer(new ExpectedFigures(ConstantFigures::literal('one')))]);

        // then
        $assertion->assertPatternRepresents('@', [new Placeholder(new Flags(''), new LiteralToken('one'))]);
    }

    /**
     * @test
     */
    public function testPatternFlags()
    {
        // given
        $assertion = PatternEntitiesAssertion::withConsumers([new FiguresPlaceholderConsumer(new ExpectedFigures(ConstantFigures::literal('one')))]);

        // then
        $assertion->assertPatternFlagsRepresent('@', 'xi', [new Placeholder(new Flags('xi'), new LiteralToken('one'))]);
    }

    /**
     * @test
     */
    public function testSubpatternFlags()
    {
        // given
        $assertion = PatternEntitiesAssertion::withConsumers([
            new GroupConsumer(),
            new FiguresPlaceholderConsumer(new ExpectedFigures(ConstantFigures::literal('one'))),
        ]);

        // then
        $assertion->assertPatternFlagsRepresent('(?m-i:@', 'xi', [
            new GroupOpenFlags('m-i'),
            new Placeholder(new Flags('xm'), new LiteralToken('one'))
        ]);
    }

    /**
     * @test
     */
    public function testPatternFlagsToFigure()
    {
        // given
        $assertion = PatternEntitiesAssertion::withConsumers([new FiguresPlaceholderConsumer(new ExpectedFigures(ConstantFigures::literal('one')))]);

        // then
        $assertion->assertPatternFlagsRepresent('@', 'xi', [new Placeholder(new Flags('xi'), new LiteralToken('one'))]);
    }
}

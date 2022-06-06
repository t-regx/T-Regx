<?php
namespace Test\Unit\CleanRegex\Internal\Prepared\Parser\Consumer;

use PHPUnit\Framework\TestCase;
use Test\Fakes\CleanRegex\Internal\Prepared\Figure\ConstantFigures;
use Test\Utils\PatternEntitiesAssertion;
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
        $assertion->assertPatternRepresents('@', [new Placeholder(new LiteralToken('one'))], 'one');
    }

    /**
     * @test
     */
    public function testPatternFlags()
    {
        // given
        $assertion = PatternEntitiesAssertion::withConsumers([new FiguresPlaceholderConsumer(new ExpectedFigures(ConstantFigures::literal('one')))]);

        // then
        $assertion->assertPatternFlagsRepresent('@', 'xi', [new Placeholder(new LiteralToken('one'))], 'one');
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
            new Placeholder(new LiteralToken('one'))
        ], '(?m-i:one');
    }

    /**
     * @test
     */
    public function testPatternFlagsToFigure()
    {
        // given
        $assertion = PatternEntitiesAssertion::withConsumers([new FiguresPlaceholderConsumer(new ExpectedFigures(ConstantFigures::literal('one')))]);

        // then
        $assertion->assertPatternFlagsRepresent('@', 'xi', [new Placeholder(new LiteralToken('one'))], 'one');
    }
}

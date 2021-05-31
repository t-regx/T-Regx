<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Prepared\Parser\Consumer;

use PHPUnit\Framework\TestCase;
use Test\Utils\EntityFailAssertion;
use Test\Utils\PatternEntitiesAssertion;
use TRegx\CleanRegex\Internal\Prepared\Parser\Consumer\CommentConsumer;
use TRegx\CleanRegex\Internal\Prepared\Parser\Consumer\GroupCloseConsumer;
use TRegx\CleanRegex\Internal\Prepared\Parser\Consumer\GroupConsumer;
use TRegx\CleanRegex\Internal\Prepared\Parser\Consumer\LiteralConsumer;
use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\Comment;
use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\GroupClose;
use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\GroupOpenFlags;

class CommentConsumerTest extends TestCase
{
    /**
     * @test
     */
    public function shouldFailToParseComment()
    {
        // given
        $assertion = new EntityFailAssertion($this, [new CommentConsumer()]);

        // then
        $assertion->assertPatternFails('#');
    }

    /**
     * @test
     */
    public function shouldParseCommentOnSubpatternExtended()
    {
        // given
        $assertion = PatternEntitiesAssertion::withConsumers([
            new GroupConsumer(),
            new CommentConsumer(),
        ]);

        // then
        $assertion->assertPatternRepresents("(?x:#boo\n", [
            new GroupOpenFlags('x'),
            new Comment('boo', true)
        ]);
    }

    /**
     * @test
     */
    public function shouldFailOnConstructiveSubpatternClosed()
    {
        // given
        $assertion = PatternEntitiesAssertion::withConsumers([
            new GroupConsumer(),
            new GroupCloseConsumer(),
            new CommentConsumer(),
            new LiteralConsumer()
        ]);

        // then
        $assertion->assertPatternRepresents('(?x:)#boo', [
            new GroupOpenFlags('x'),
            new GroupClose(),
            '#boo',
        ]);
    }

    /**
     * @test
     */
    public function shouldFailOnConstructiveSubpatternDestructiveSubpattern()
    {
        // given
        $assertion = PatternEntitiesAssertion::withConsumers([
            new GroupConsumer(),
            new CommentConsumer(),
            new LiteralConsumer()
        ]);

        // then
        $assertion->assertPatternRepresents('(?x:(?-x:#boo', [
            new GroupOpenFlags('x'),
            new GroupOpenFlags('-x'),
            '#boo'
        ]);
    }

    /**
     * @test
     */
    public function shouldConsumeOnConstructiveSubpatternDestructiveSubpatternClosed()
    {
        // given
        $assertion = PatternEntitiesAssertion::withConsumers([
            new GroupConsumer(),
            new GroupCloseConsumer(),
            new CommentConsumer(),
            new LiteralConsumer()
        ]);

        // then
        $assertion->assertPatternRepresents('(?x:(?-x:)#boo', [
            new GroupOpenFlags('x'),
            new GroupOpenFlags('-x'),
            new GroupClose(),
            new Comment('boo', false)
        ]);
    }

    /**
     * @test
     */
    public function shouldParseCommentOnExtended()
    {
        // given
        $assertion = PatternEntitiesAssertion::withConsumers([new CommentConsumer()]);

        // then
        $assertion->assertPatternFlagsRepresent('#boo', 'x', [new Comment('boo', false)]);
    }

    /**
     * @test
     */
    public function shouldFailOnConstructivePatternDestructiveSubpattern()
    {
        // given
        $assertion = PatternEntitiesAssertion::withConsumers([
            new GroupConsumer(),
            new CommentConsumer(),
            new LiteralConsumer()
        ]);

        // then
        $assertion->assertPatternFlagsRepresent('(?-x:#boo', 'x', [
            new GroupOpenFlags('-x'),
            '#boo'
        ]);
    }

    /**
     * @test
     */
    public function shouldEndComment()
    {
        // given
        $assertion = PatternEntitiesAssertion::withConsumers([
            new CommentConsumer(),
            new LiteralConsumer()
        ]);

        // then
        $assertion->assertPatternFlagsRepresent("#foor\nbar", 'x', [
            new Comment('foor', true),
            'bar',
        ]);
    }
}

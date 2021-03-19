<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Prepared\Parser;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\Delimiter\TrailingBackslashException;
use TRegx\CleanRegex\Internal\Prepared\Parser\TemplateParser;
use TRegx\CleanRegex\Internal\Prepared\Quotable\Factory\AlterationFactory;
use TRegx\CleanRegex\Internal\Prepared\Template\LiteralTokenValue;

class TemplateParserTest extends TestCase
{
    /**
     * @test
     */
    public function shouldParse(): void
    {
        // given
        $parser = new TemplateParser('foo:&', [new LiteralTokenValue()]);

        // when
        $result = $parser->parse('#', new AlterationFactory(''));

        // then
        $this->assertSame('foo:&', $result->quote('#'));
    }

    /**
     * @test
     */
    public function shouldThrow_onTrailingBackslash(): void
    {
        // given
        $parser = new TemplateParser('foo:&\\', [new LiteralTokenValue()]);

        // then
        $this->expectException(TrailingBackslashException::class);

        // when
        $parser->parse('#', new AlterationFactory(''));
    }

    /**
     * @test
     */
    public function shouldGetDelimiterable(): void
    {
        // given
        $parser = new TemplateParser('foo:&/@', []);

        // when
        $delimiterable = $parser->getDelimiterable();

        // then
        $this->assertSame('foo:&/@', $delimiterable);
    }
}

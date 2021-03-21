<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Prepared\Parser;

use PHPUnit\Framework\TestCase;
use Test\Utils\Impl\RawToken;
use Test\Utils\Impl\ThrowToken;
use TRegx\CleanRegex\Internal\Delimiter\TrailingBackslashException;
use TRegx\CleanRegex\Internal\Prepared\Parser\TemplateParser;
use TRegx\CleanRegex\Internal\Prepared\Quotable\Factory\AlterationFactory;

class TemplateParserTest extends TestCase
{
    /**
     * @test
     */
    public function shouldParse(): void
    {
        // given
        $parser = new TemplateParser('foo:&', [new RawToken('W', '#')]);

        // when
        $result = $parser->parse('#', new AlterationFactory(''));

        // then
        $this->assertSame('foo:W', $result->quote('#'));
    }

    /**
     * @test
     */
    public function shouldThrow_onTrailingBackslash(): void
    {
        // given
        $parser = new TemplateParser('foo:&\\', [new ThrowToken()]);

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

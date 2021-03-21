<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Prepared\Parser;

use PHPUnit\Framework\TestCase;
use Test\Utils\Impl\Identity;
use TRegx\CleanRegex\Internal\Delimiter\TrailingBackslashException;
use TRegx\CleanRegex\Internal\Prepared\Parser\BindingParser;
use TRegx\CleanRegex\Internal\Prepared\Quotable\Factory\AlterationFactory;
use TRegx\CleanRegex\Internal\Prepared\Template\NoTemplate;

class BindingParserTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetDelimiterable(): void
    {
        // given
        $parser = new BindingParser('input', ['foo' => 'bar'], new NoTemplate());

        // when
        $delimiterable = $parser->getDelimiterable();

        // then
        $this->assertSame('input', $delimiterable);
    }

    /**
     * @test
     */
    public function shouldParseWithoutReiterating(): void
    {
        // given
        $parser = new BindingParser('string @foo', ['foo' => '@foo `foo` `foo`'], new NoTemplate());

        // when
        $result = $parser->parse('/', new AlterationFactory(''));

        // then
        $this->assertSame('string @foo\ `foo`\ `foo`', $result->quote('/'));
    }

    /**
     * @test
     */
    public function shouldThrow_trailingSlash(): void
    {
        // given
        $parser = new BindingParser('string @foo\\', ['foo' => 'foo'], new NoTemplate());

        // then
        $this->expectException(TrailingBackslashException::class);

        // when
        $parser->parse('/', new Identity());
    }
}

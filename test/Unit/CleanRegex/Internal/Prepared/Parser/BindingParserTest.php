<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Prepared\Parser;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\Prepared\Parser\BindingParser;
use TRegx\CleanRegex\Internal\Prepared\Parser\IgnoreStrategy;
use TRegx\CleanRegex\Internal\Prepared\Quoteable\Factory\AlterationFactory;

class BindingParserTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetDelimiterable(): void
    {
        // given
        $parser = new BindingParser('input', ['foo' => 'bar'], new IgnoreStrategy());

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
        $parser = new BindingParser('string @foo', ['foo' => '@foo `foo` `foo`'], new IgnoreStrategy());

        // when
        $result = $parser->parse('/', new AlterationFactory(''));

        // then
        $this->assertSame('string @foo\ `foo`\ `foo`', $result->quote('/'));
    }
}

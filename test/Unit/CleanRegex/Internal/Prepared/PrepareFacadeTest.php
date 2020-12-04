<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Prepared;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\Prepared\Parser\Parser;
use TRegx\CleanRegex\Internal\Prepared\PrepareFacade;
use TRegx\CleanRegex\Internal\Prepared\Quoteable\UserInputQuoteable;

class PrepareFacadeTest extends TestCase
{
    /**
     * @test
     */
    public function shouldReturnPattern()
    {
        // given
        $facade = new PrepareFacade($this->parser('Foo', '/', 'bar'), false, '');

        // when
        $pattern = $facade->getPattern();

        // then
        $this->assertEquals('/bar/', $pattern);
    }

    /**
     * @test
     */
    public function shouldReturnPattern_pcre()
    {
        // given
        $facade = new PrepareFacade($this->parser('/Foo/', '/', 'bar'), true, '');

        // when
        $pattern = $facade->getPattern();

        // then
        $this->assertEquals('bar', $pattern);
    }

    /**
     * @test
     */
    public function shouldParseWithDelimiter()
    {
        // given
        $facade = new PrepareFacade($this->parser('/#', '%', 'bar'), false, '');

        // when
        $pattern = $facade->getPattern();

        // then
        $this->assertEquals('%bar%', $pattern);
    }

    /**
     * @test
     */
    public function shouldParseWithDelimiter_pcre()
    {
        // given
        $facade = new PrepareFacade($this->parser('~Foo~', '~', 'Word'), true, '');

        // when
        $pattern = $facade->getPattern();

        // then
        $this->assertEquals('Word', $pattern);
    }

    /**
     * @test
     */
    public function shouldQuoteParserResult()
    {
        // given
        $facade = new PrepareFacade($this->parser('Foo /#%', '~', 'HEAD~2'), false, '');

        // when
        $pattern = $facade->getPattern();

        // then
        $this->assertEquals('~HEAD\~2~', $pattern);
    }

    /**
     * @test
     */
    public function shouldQuoteParserResult_pcre()
    {
        // given
        $facade = new PrepareFacade($this->parser('~Foo~', '~', 'HEAD~2'), true, '');

        // when
        $pattern = $facade->getPattern();

        // then
        $this->assertEquals('HEAD\~2', $pattern);
    }

    public function parser(string $input, string $expectDelimiter, string $result): Parser
    {
        /** @var Parser|MockObject $parser */
        $parser = $this->getMockBuilder(Parser::class)->setMethods(['getDelimiterable', 'parse'])->getMock();
        $parser->expects($this->once())->method('getDelimiterable')->willReturn($input);
        $parser->expects($this->once())->method('parse')->with($this->equalTo($expectDelimiter))->willReturn(new UserInputQuoteable($result));
        return $parser;
    }
}

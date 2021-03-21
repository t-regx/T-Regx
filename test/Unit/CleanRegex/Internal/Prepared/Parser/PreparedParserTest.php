<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Prepared\Parser;

use PHPUnit\Framework\TestCase;
use Test\Utils\Impl\Identity;
use TRegx\CleanRegex\Internal\Delimiter\TrailingBackslashException;
use TRegx\CleanRegex\Internal\Prepared\Parser\PreparedParser;
use TRegx\CleanRegex\Internal\Prepared\Quotable\Factory\AlterationFactory;

class PreparedParserTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetDelimiterable(): void
    {
        // given
        $parser = new PreparedParser(['/#', [')'], '(']);

        // when
        $delimiterable = $parser->getDelimiterable();

        // then
        $this->assertSame('/#(', $delimiterable);
    }

    /**
     * @test
     */
    public function shouldThrow_trailingSlash(): void
    {
        // given
        $parser = new PreparedParser(['string @\\']);

        // then
        $this->expectException(TrailingBackslashException::class);

        // when
        $parser->parse('/', new Identity());
    }

    /**
     * @test
     */
    public function shouldNotThrow_trailingSlash_notLast(): void
    {
        // given
        $parser = new PreparedParser(['first\\', 'last']);

        // when
        $parsed = $parser->parse('/', new Identity());

        // then
        $this->assertSame('first\\last', $parsed->quote('/'));
    }

    /**
     * @test
     */
    public function shouldNotThrow_trailingSlash_lastArray(): void
    {
        // given
        $parser = new PreparedParser(['first\\', ['last\\']]);

        // when
        $parsed = $parser->parse('/', new AlterationFactory(''));

        // then
        $this->assertSame('first\\last\\\\', $parsed->quote('/'));
    }
}

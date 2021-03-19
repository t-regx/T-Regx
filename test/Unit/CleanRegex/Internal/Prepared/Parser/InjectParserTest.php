<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Prepared\Parser;

use PHPUnit\Framework\TestCase;
use Test\Utils\Identity;
use TRegx\CleanRegex\Internal\Delimiter\TrailingBackslashException;
use TRegx\CleanRegex\Internal\Prepared\Parser\InjectParser;
use TRegx\CleanRegex\Internal\Prepared\Template\IgnoreStrategy;

class InjectParserTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetDelimiterable(): void
    {
        // given
        $parser = new InjectParser('/#', [], new IgnoreStrategy());

        // when
        $delimiterable = $parser->getDelimiterable();

        // then
        $this->assertSame('/#', $delimiterable);
    }

    /**
     * @test
     */
    public function shouldThrow_trailingSlash(): void
    {
        // given
        $parser = new InjectParser('string @\\', ['foo'], new IgnoreStrategy());

        // then
        $this->expectException(TrailingBackslashException::class);

        // when
        $parser->parse('/', new Identity());
    }
}

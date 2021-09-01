<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Prepared\Figure;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\Prepared\Figure\TokenFigures;
use TRegx\CleanRegex\Internal\Prepared\Template\AlternationToken;
use TRegx\CleanRegex\Internal\Prepared\Template\LiteralToken;
use TRegx\CleanRegex\Internal\Prepared\Template\NullToken;

/**
 * @covers \TRegx\CleanRegex\Internal\Prepared\Figure\TokenFigures
 */
class TokenFiguresTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetFirst()
    {
        // given
        $figures = new TokenFigures([new LiteralToken('foo')]);

        // when
        $token = $figures->nextToken();

        // then
        $this->assertEquals(new LiteralToken('foo'), $token);
    }

    /**
     * @test
     */
    public function shouldGetNext()
    {
        // given
        $figures = new TokenFigures([new NullToken(), new NullToken(), new LiteralToken('bar')]);
        $figures->nextToken();
        $figures->nextToken();

        // when
        $token = $figures->nextToken();

        // then
        $this->assertEquals(new LiteralToken('bar'), $token);
    }

    /**
     * @test
     */
    public function shouldGetUnexpected()
    {
        // given
        $figures = new TokenFigures([]);

        // when
        $token = $figures->nextToken();

        // then
        $this->assertEquals(new NullToken(), $token);
    }

    /**
     * @test
     */
    public function shouldIgnoreInternalArrayPointer()
    {
        // given
        $tokens = [new LiteralToken('foo'), new LiteralToken('bar')];
        \next($tokens);
        $figures = new TokenFigures($tokens);

        // when
        $token = $figures->nextToken();

        // then
        $this->assertEquals(new LiteralToken('foo'), $token);
    }

    /**
     * @test
     */
    public function shouldAlternation()
    {
        // given
        $figures = new TokenFigures([new AlternationToken(['foo'])]);

        // when
        $token = $figures->nextToken();

        // then
        $this->assertEquals(new AlternationToken(['foo']), $token);
    }
}

<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Prepared\Figure;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\Prepared\Figure\TokenFigures;
use TRegx\CleanRegex\Internal\Prepared\Template\AlternationToken;
use TRegx\CleanRegex\Internal\Prepared\Template\LiteralToken;
use TRegx\CleanRegex\Internal\Prepared\Template\NullToken;

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
        $literal = $figures->nextToken();

        // then
        $this->assertEquals(new LiteralToken('foo'), $literal);
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
        $literal = $figures->nextToken();

        // then
        $this->assertEquals(new LiteralToken('bar'), $literal);
    }

    /**
     * @test
     */
    public function shouldGetUnexpected()
    {
        // given
        $figures = new TokenFigures([]);

        // when
        $literal = $figures->nextToken();

        // then
        $this->assertEquals(new NullToken(), $literal);
    }

    /**
     * @test
     */
    public function shouldIgnoreInternalArrayPointer()
    {
        // given
        $array = [new LiteralToken('foo'), new LiteralToken('bar')];
        \next($array);
        $placeholders = new TokenFigures($array);

        // when
        $value = $placeholders->nextToken();

        // then
        $this->assertEquals(new LiteralToken('foo'), $value);
    }

    /**
     * @test
     */
    public function shouldAlternation()
    {
        // given
        $figures = new TokenFigures([new AlternationToken(['foo'])]);

        // when
        $literal = $figures->nextToken();

        // then
        $this->assertEquals(new AlternationToken(['foo']), $literal);
    }
}

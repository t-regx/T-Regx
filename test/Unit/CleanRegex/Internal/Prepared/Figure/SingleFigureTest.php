<?php
namespace Test\Unit\CleanRegex\Internal\Prepared\Figure;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\Prepared\Figure\SingleFigure;
use TRegx\CleanRegex\Internal\Prepared\Template\LiteralToken;
use TRegx\CleanRegex\Internal\Prepared\Template\NullToken;
use TypeError;

/**
 * @covers \TRegx\CleanRegex\Internal\Prepared\Figure\SingleFigure
 */
class SingleFigureTest extends TestCase
{
    /**
     * @test
     */
    public function test()
    {
        // given
        $figure = new SingleFigure(new LiteralToken('foo'));

        // when
        $first = $figure->nextToken();
        $second = $figure->nextToken();

        // then
        $this->assertEquals(new LiteralToken('foo'), $first);
        $this->assertEquals(new NullToken(), $second);
    }

    /**
     * @test
     */
    public function shouldNotAcceptNullAsArgument()
    {
        // given
        $this->expectException(TypeError::class);

        // when
        new SingleFigure(null);
    }
}

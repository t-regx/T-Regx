<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Prepared\Figure;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Test\Utils\ExactExceptionMessage;
use Test\Utils\TestCasePasses;
use TRegx\CleanRegex\Internal\Prepared\Figure\InjectFigures;
use TRegx\CleanRegex\Internal\Prepared\Template\AlternationToken;
use TRegx\CleanRegex\Internal\Prepared\Template\LiteralToken;
use UnderflowException;
use function next;

/**
 * @covers \TRegx\CleanRegex\Internal\Prepared\Figure\InjectFigures
 */
class InjectFiguresTest extends TestCase
{
    use TestCasePasses, ExactExceptionMessage;

    /**
     * @test
     */
    public function shouldGetFirstFigure()
    {
        // given
        $figures = new InjectFigures(['foo']);

        // when
        $token = $figures->nextToken();

        // then
        $this->assertEquals(new LiteralToken('foo'), $token);
    }

    /**
     * @test
     */
    public function shouldGetAlternationFigure()
    {
        // given
        $figures = new InjectFigures([['foo', 'bar']]);

        // when
        $token = $figures->nextToken();

        // then
        $this->assertEquals(new AlternationToken(['foo', 'bar']), $token);
    }

    /**
     * @test
     */
    public function shouldGetFirstTwoFigures()
    {
        // given
        $figures = new InjectFigures(['foo', 'bar']);

        // when
        $figure1 = $figures->nextToken();
        $figure2 = $figures->nextToken();

        // then
        $this->assertEquals(new LiteralToken('foo'), $figure1);
        $this->assertEquals(new LiteralToken('bar'), $figure2);
    }

    /**
     * @test
     */
    public function shouldThrowForMissingSecondFigure()
    {
        // given
        $figures = new InjectFigures(['foo']);
        $figures->nextToken();

        // then
        $this->expectException(UnderflowException::class);

        // when
        $figures->nextToken();
    }

    /**
     * @test
     */
    public function shouldThrowForInvalidFigureType()
    {
        // given
        $figures = new InjectFigures([21]);

        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Invalid inject figure type. Expected string, but integer (21) given");

        // when
        $figures->nextToken();
    }

    /**
     * @test
     */
    public function shouldThrowForInvalidFigureTypeStringKey()
    {
        // given
        $figures = new InjectFigures(['foo', 'foo' => 4]);
        $figures->nextToken();

        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Invalid inject figure type. Expected string, but integer (4) given");

        // when
        $figures->nextToken();
    }

    /**
     * @test
     */
    public function shouldIgnoreInternalArrayPointer()
    {
        // given
        $array = ['foo', 'bar'];
        next($array);
        $figures = new InjectFigures($array);

        // when
        $token = $figures->nextToken();

        // then
        $this->assertEquals(new LiteralToken('foo'), $token);
    }

    /**
     * @test
     */
    public function shouldCountFigures()
    {
        // given
        $figures = new InjectFigures(['foo', 'bar']);
        $figures->nextToken();

        // when
        $count = $figures->count();

        // then
        $this->assertSame(2, $count);
    }
}

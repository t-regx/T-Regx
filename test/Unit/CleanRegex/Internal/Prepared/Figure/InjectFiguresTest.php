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
        $placeholders = new InjectFigures(['foo']);

        // when
        $value = $placeholders->nextToken();

        // then
        $this->assertEquals(new LiteralToken('foo'), $value);
    }

    /**
     * @test
     */
    public function shouldGetAlternationFigure()
    {
        // given
        $placeholders = new InjectFigures([['foo', 'bar']]);

        // when
        $value = $placeholders->nextToken();

        // then
        $this->assertEquals(new AlternationToken(['foo', 'bar']), $value);
    }

    /**
     * @test
     */
    public function shouldGetFirstTwoFigures()
    {
        // given
        $placeholders = new InjectFigures(['foo', 'bar']);

        // when
        $figure1 = $placeholders->nextToken();
        $figure2 = $placeholders->nextToken();

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
        $placeholders = new InjectFigures(['foo']);
        $placeholders->nextToken();

        // then
        $this->expectException(UnderflowException::class);

        // when
        $placeholders->nextToken();
    }

    /**
     * @test
     */
    public function shouldThrowForInvalidFigureType()
    {
        // given
        $placeholders = new InjectFigures([21]);

        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Invalid figure type [#0 => integer (21)]. Expected string, but integer (21) given");

        // when
        $placeholders->nextToken();
    }

    /**
     * @test
     */
    public function shouldThrowForInvalidFigureTypeStringKey()
    {
        // given
        $placeholders = new InjectFigures(['foo', 'foo' => 4]);
        $placeholders->nextToken();

        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Invalid figure type ['foo' => integer (4)]. Expected string, but integer (4) given");

        // when
        $placeholders->nextToken();
    }

    /**
     * @test
     */
    public function shouldIgnoreInternalArrayPointer()
    {
        // given
        $array = ['foo', 'bar'];
        next($array);
        $placeholders = new InjectFigures($array);

        // when
        $value = $placeholders->nextToken();

        // then
        $this->assertEquals(new LiteralToken('foo'), $value);
    }

    /**
     * @test
     */
    public function shouldCountFigures()
    {
        // given
        $placeholders = new InjectFigures(['foo', 'bar']);
        $placeholders->nextToken();

        // when
        $count = $placeholders->count();

        // then
        $this->assertSame(2, $count);
    }
}

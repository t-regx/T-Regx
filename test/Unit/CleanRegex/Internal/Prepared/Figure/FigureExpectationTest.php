<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Prepared\Figure;

use PHPUnit\Framework\TestCase;
use Test\Fakes\CleanRegex\Internal\Prepared\Figure\ConstantFigures;
use Test\Utils\TestCasePasses;
use TRegx\CleanRegex\Internal\Prepared\Figure\FigureExpectation;
use TRegx\CleanRegex\Internal\Prepared\Figure\PlaceholderFigureException;
use TRegx\CleanRegex\Internal\Prepared\Template\AlterationToken;

/**
 * @covers \TRegx\CleanRegex\Internal\Prepared\Figure\FigureExpectation
 */
class FigureExpectationTest extends TestCase
{
    use TestCasePasses;

    /**
     * @test
     */
    public function shouldGetNoExpectedFigures()
    {
        // given
        $expectation = new FigureExpectation(new ConstantFigures(0));

        // when
        $expectation->meetExpectation();

        // then
        $this->pass();
    }

    /**
     * @test
     */
    public function shouldExpectThree()
    {
        // given
        $expectation = new FigureExpectation(new ConstantFigures(3));
        $expectation->expectNext();
        $expectation->expectNext();
        $expectation->expectNext();

        // when
        $expectation->meetExpectation();

        // then
        $this->pass();
    }

    /**
     * @test
     */
    public function shouldThrowForNotEnoughFigures()
    {
        // given
        $expectation = new FigureExpectation(new ConstantFigures(2));
        $expectation->expectNext();
        $expectation->expectNext();
        $expectation->expectNext();
        $expectation->expectNext();

        // then
        $this->expectException(PlaceholderFigureException::class);
        $this->expectExceptionMessage('Not enough corresponding figures supplied. Used 4 placeholders, but 2 figures supplied.');

        // when
        $expectation->meetExpectation();
    }

    /**
     * @test
     */
    public function shouldThrowForSuperfluousFigures()
    {
        // given
        $expectation = new FigureExpectation(new ConstantFigures(5, new AlterationToken(['foo', 'bar'])));
        $expectation->expectNext();
        $expectation->expectNext();

        // then
        $this->expectException(PlaceholderFigureException::class);
        $this->expectExceptionMessage('Found a superfluous figure: array (2). Used 2 placeholders, but 5 figures supplied.');

        // when
        $expectation->meetExpectation();
    }
}

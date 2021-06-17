<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Prepared\Figure;

use PHPUnit\Framework\TestCase;
use Test\Utils\Impl\ConstantFigures;
use Test\Utils\TestCasePasses;
use TRegx\CleanRegex\Internal\Prepared\Figure\FigureExpectation;
use TRegx\CleanRegex\Internal\Prepared\Figure\PlaceholderFigureException;
use TRegx\CleanRegex\Internal\Prepared\Template\AlternationToken;

class FigureExpectationTest extends TestCase
{
    use TestCasePasses;

    /**
     * @test
     */
    public function shouldGetNoExpectedFigures()
    {
        // given
        $expectedFigures = new FigureExpectation(new ConstantFigures(0));

        // when
        $expectedFigures->meetExpectation();

        // then
        $this->pass();
    }

    /**
     * @test
     */
    public function shouldExpectThree()
    {
        // given
        $expectedFigures = new FigureExpectation(new ConstantFigures(3));
        $expectedFigures->expectNext();
        $expectedFigures->expectNext();
        $expectedFigures->expectNext();

        // when
        $expectedFigures->meetExpectation();

        // then
        $this->pass();
    }

    /**
     * @test
     */
    public function shouldThrowForNotEnoughFigures()
    {
        // given
        $expectedFigures = new FigureExpectation(new ConstantFigures(2));
        $expectedFigures->expectNext();
        $expectedFigures->expectNext();
        $expectedFigures->expectNext();
        $expectedFigures->expectNext();

        // then
        $this->expectException(PlaceholderFigureException::class);
        $this->expectExceptionMessage('Not enough corresponding figures supplied. Used 4 placeholders, but 2 figures supplied.');

        // when
        $expectedFigures->meetExpectation();
    }

    /**
     * @test
     */
    public function shouldThrowForSuperfluousFigures()
    {
        // given
        $expectedFigures = new FigureExpectation(new ConstantFigures(5, new AlternationToken(['foo', 'bar'])));
        $expectedFigures->expectNext();
        $expectedFigures->expectNext();

        // then
        $this->expectException(PlaceholderFigureException::class);
        $this->expectExceptionMessage('Found a superfluous figure: array (2). Used 2 placeholders, but 5 figures supplied.');

        // when
        $expectedFigures->meetExpectation();
    }
}

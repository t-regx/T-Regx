<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Prepared\Figure;

use PHPUnit\Framework\TestCase;
use Test\Utils\ExactExceptionMessage;
use Test\Utils\Impl\ConstantFigures;
use Test\Utils\TestCasePasses;
use TRegx\CleanRegex\Internal\Prepared\Figure\ExpectedFigures;
use TRegx\CleanRegex\Internal\Prepared\Figure\PlaceholderFigureException;
use TRegx\CleanRegex\Internal\Prepared\Template\LiteralToken;
use TRegx\CleanRegex\Internal\Prepared\Template\NullToken;

class ExpectedFiguresTest extends TestCase
{
    use TestCasePasses, ExactExceptionMessage;

    /**
     * @test
     */
    public function shouldTest()
    {
        // given
        $figure = new ExpectedFigures(new ConstantFigures(1, new LiteralToken('one')));

        // when
        $token = $figure->nextToken();

        // then
        $this->assertEquals(new LiteralToken('one'), $token);
    }

    /**
     * @test
     */
    public function shouldNotMeetExpectation()
    {
        // given
        $figure = new ExpectedFigures(new ConstantFigures(0, new NullToken()));
        $figure->nextToken();

        // when
        $this->expectException(PlaceholderFigureException::class);
        $this->expectExceptionMessage('Not enough corresponding figures supplied. Used 1 placeholders, but 0 figures supplied.');

        // then
        $figure->meetExpectation();
    }

    /**
     * @test
     */
    public function shouldNotMeetExpectationSuperfluous()
    {
        // given
        $figure = new ExpectedFigures(new ConstantFigures(1, new LiteralToken('foo')));

        // then
        $this->expectException(PlaceholderFigureException::class);
        $this->expectExceptionMessage("Found a superfluous figure: string ('foo'). Used 0 placeholders, but 1 figures supplied.");

        // when
        $figure->meetExpectation();
    }

    /**
     * @test
     */
    public function shouldMeetEmptyExpectations()
    {
        // given
        $figure = new ExpectedFigures(new ConstantFigures(0));

        // when
        $figure->meetExpectation();

        // then
        $this->pass();
    }
}

<?php
namespace Test\Feature\CleanRegex\_figures\template\alteration;

use PHPUnit\Framework\TestCase;
use Test\Utils\TestCase\TestCaseExactMessage;
use TRegx\CleanRegex\Exception\PlaceholderFigureException;
use TRegx\CleanRegex\Pattern;

class Test extends TestCase
{
    use TestCaseExactMessage;

    /**
     * @test
     */
    public function shouldThrowForSuperfluousFigures()
    {
        // then
        $this->expectException(PlaceholderFigureException::class);
        $this->expectExceptionMessage('Found a superfluous figure: array (5). Used 0 placeholders, but 1 figures supplied.');
        // when
        Pattern::template('pattern')->alteration(['foo', 'bar', 'cat', 'door', 'fridge']);
    }

    /**
     * @test
     */
    public function shouldThrowForUnderflowFigures()
    {
        // then
        $this->expectException(PlaceholderFigureException::class);
        $this->expectExceptionMessage('Not enough corresponding figures supplied. Used 2 placeholders, but 1 figures supplied.');
        // when
        Pattern::template('@@')->alteration(['foo', 'bar', 'cat', 'door', 'fridge']);
    }
}

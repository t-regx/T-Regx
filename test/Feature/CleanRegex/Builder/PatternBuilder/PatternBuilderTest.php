<?php
namespace Test\Feature\TRegx\CleanRegex\Builder\PatternBuilder;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\Prepared\Figure\PlaceholderFigureException;
use TRegx\CleanRegex\Pattern;

class PatternBuilderTest extends TestCase
{
    /**
     * @test
     */
    public function shouldBuild_inject()
    {
        // given
        $pattern = Pattern::builder()->inject('You/her, (are|is) @ (you|her)', [
            'real? (or are you not real?)'
        ]);

        // when
        $pattern = $pattern->delimited();

        // then
        $this->assertSame('#You/her, (are|is) real\?\ \(or\ are\ you\ not\ real\?\) (you|her)#', $pattern);
    }

    /**
     * @test
     */
    public function shouldThrowForMismatchedNumberOfPlaceholders()
    {
        // then
        $this->expectException(PlaceholderFigureException::class);
        $this->expectExceptionMessage("Found a superfluous figure: string ('bar'). Used 1 placeholders, but 4 figures supplied.");

        // when
        Pattern::builder()->inject('You/her, (are|is) @ (you|her)', [
            'foo',
            'bar',
            'cat',
            'door',
        ]);
    }
}

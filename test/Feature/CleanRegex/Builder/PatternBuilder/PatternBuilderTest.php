<?php
namespace Test\Feature\TRegx\CleanRegex\Builder\PatternBuilder;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\Prepared\Figure\PlaceholderFigureException;
use TRegx\CleanRegex\Pattern;

/**
 * @coversNothing
 */
class PatternBuilderTest extends TestCase
{
    /**
     * @test
     */
    public function shouldBuild_inject()
    {
        // given
        $figure = 'real? (or are you not real?)';
        $pattern = Pattern::inject('You/her, (are|is) @ (you|her)', [$figure]);

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
        // given
        $figures = ['foo', 'bar', 'cat', 'door'];

        // then
        $this->expectException(PlaceholderFigureException::class);
        $this->expectExceptionMessage("Found a superfluous figure: string ('bar'). Used 1 placeholders, but 4 figures supplied.");

        // when
        Pattern::inject('You/her, (are|is) @ (you|her)', $figures);
    }

    /**
     * @test
     */
    public function shouldBuild_template_alteration_build()
    {
        // given
        $pattern = Pattern::template('You/her, @ (her)', 's')->alteration(['{hi}', '50#'])->build();

        // when
        $pattern = $pattern->delimited();

        // then
        $this->assertSame('#You/her, (?:\{hi\}|50\#) (her)#s', $pattern);
    }
}

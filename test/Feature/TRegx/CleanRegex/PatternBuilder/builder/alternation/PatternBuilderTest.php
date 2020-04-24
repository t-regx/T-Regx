<?php
namespace Test\Feature\TRegx\CleanRegex\PatternBuilder\builder\alternation;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\PatternBuilder;

class PatternBuilderTest extends TestCase
{
    /**
     * @test
     */
    public function shouldBuild_prepared()
    {
        // given
        $pattern = PatternBuilder::builder()->prepare(['You/her, (are|is) ', [['Hello #5', 'Yes?:)']], ' (you|her)']);

        // when
        $pattern = $pattern->delimited();

        // then
        $this->assertEquals('#You/her, (are|is) (?:Hello\ \#5|Yes\?\:\)) (you|her)#', $pattern);
    }

    /**
     * @test
     */
    public function shouldBuild_bind()
    {
        // given
        $pattern = PatternBuilder::builder()->bind('You/her, (are|is) @question (you|her)', [
            'question' => ['Hello #5', 'Yes?:)']
        ]);

        // when
        $pattern = $pattern->delimited();

        // then
        $this->assertEquals('#You/her, (are|is) (?:Hello\ \#5|Yes\?\:\)) (you|her)#', $pattern);
    }

    /**
     * @test
     */
    public function shouldBuild_inject()
    {
        // given
        $pattern = PatternBuilder::builder()->inject('You/her, (are|is) @ (you|her)', [
            ['Hello #5', 'Yes?:)']
        ]);

        // when
        $pattern = $pattern->delimited();

        // then
        $this->assertEquals('#You/her, (are|is) (?:Hello\ \#5|Yes\?\:\)) (you|her)#', $pattern);
    }
}

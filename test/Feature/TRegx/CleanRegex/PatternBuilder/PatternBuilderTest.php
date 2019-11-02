<?php
namespace Test\Feature\TRegx\CleanRegex\PatternBuilder;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Pattern;
use TRegx\CleanRegex\PatternBuilder;

class PatternBuilderTest extends TestCase
{
    /**
     * @test
     */
    public function shouldBuild_prepared()
    {
        // given
        $pattern = PatternBuilder::builder()->prepare(['You/her, (are|is) ', ['real? (or are you not real?)'], ' (you|her)']);

        // when
        $pattern = $pattern->delimiter();

        // then
        $this->assertEquals('#You/her, (are|is) real\? \(or are you not real\?\) (you|her)#', $pattern);
    }

    /**
     * @test
     */
    public function shouldBuild_bind()
    {
        // given
        $pattern = PatternBuilder::builder()->bind('You/her, (are|is) @question (you|her)', [
            'question' => 'real? (or are you not real?)'
        ]);

        // when
        $pattern = $pattern->delimiter();

        // then
        $this->assertEquals('#You/her, (are|is) real\? \(or are you not real\?\) (you|her)#', $pattern);
    }

    /**
     * @test
     */
    public function shouldBuild_inject()
    {
        // given
        $pattern = PatternBuilder::builder()->inject('You/her, (are|is) @ (you|her)', [
            'real? (or are you not real?)'
        ]);

        // when
        $pattern = $pattern->delimiter();

        // then
        $this->assertEquals('#You/her, (are|is) real\? \(or are you not real\?\) (you|her)#', $pattern);
    }

    /**
     * @test
     */
    public function shouldBuild_compose()
    {
        // given
        $name = 'Frodo';
        $pattern = PatternBuilder::compose([
            pattern('^Fro'),
            Pattern::of('rod'),
            Pattern::pcre('/do$/')
        ]);

        // when
        $matches = $pattern->allMatch($name);

        // then
        $this->assertTrue($matches);
    }
}

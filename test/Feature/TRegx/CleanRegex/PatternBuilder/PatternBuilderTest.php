<?php
namespace Test\Feature\TRegx\CleanRegex\PatternBuilder;

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
        $pattern = PatternBuilder::prepare(['You/her, (are|is) ', ['real? (or are you not real?)'], ' (you|her)']);

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
        $pattern = PatternBuilder::inject('You/her, (are|is) @question (you|her)', [
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
    public function shouldBuild_compose()
    {
        // given
        $name = 'Frodo';
        $pattern = PatternBuilder::compose([
            pattern('^Fro'),
            'rod',
            '/do$/'
        ]);

        // when
        $matches = $pattern->allMatch($name);

        // then
        $this->assertTrue($matches);
    }
}

<?php
namespace Test\Integration\TRegx\CleanRegex\Analyze\Simplify;

use PHPUnit\Framework\TestCase;

class PatternSimplifierTest extends TestCase
{
    /**
     * @test
     */
    public function shouldSimplify()
    {
        // when
        $simplified = pattern('(?:a|b|c){1,}')->analyze()->simplify();

        // then
        $this->assertEquals('[abc]+', $simplified);
    }

    /**
     * @test
     */
    public function shouldSimplify_withDelimiters()
    {
        // when
        $simplified = pattern('/(?:a|b|c){1,}/')->analyze()->simplify();

        // then
        $this->assertEquals('/[abc]+/', $simplified);
    }

    /**
     * @test
     */
    public function shouldSimplify_withFlag()
    {
        // when
        $simplified = pattern('(?:a|b|c){1,}', 'i')->analyze()->simplify();

        // then
        $this->assertEquals('[abc]+', $simplified);
    }

    /**
     * @test
     */
    public function shouldSimplify_withDelimiters_withoutFlag()
    {
        // when
        $simplified = pattern('/(?:a|b|c){1,}/', 'i')->analyze()->simplify();

        // then
        $this->assertEquals('[abc]+', $simplified);
    }
}

<?php
namespace Test\Unit\TRegx\CleanRegex\MatchesPattern\fails;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\InternalPattern;
use TRegx\CleanRegex\MatchesPattern;

class MatchesPatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldNot_fail()
    {
        // given
        $pattern = new MatchesPattern(new InternalPattern('/[a-z]/'), 'matching');

        // when
        $result = $pattern->fails();

        // then
        $this->assertFalse($result);
    }

    /**
     * @test
     */
    public function should_fail()
    {
        // given
        $pattern = new MatchesPattern(new InternalPattern('/^[a-z]+$/'), 'not matching');

        // when
        $result = $pattern->fails();

        // then
        $this->assertTrue($result);
    }

    /**
     * @test
     */
    public function shouldNot_integer_fail()
    {
        // given
        $pattern = new MatchesPattern(new InternalPattern('/^2$/'), 2);

        // when
        $true = $pattern->fails();

        // then
        $this->assertFalse($true);
    }

    /**
     * @test
     */
    public function should_integer_fail()
    {
        // given
        $pattern = new MatchesPattern(new InternalPattern('/^3$/'), 2);

        // when
        $true = $pattern->fails();

        // then
        $this->assertTrue($true);
    }
}

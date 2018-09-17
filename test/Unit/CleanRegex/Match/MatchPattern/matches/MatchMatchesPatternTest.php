<?php
namespace Test\Unit\CleanRegex\Match;

use CleanRegex\Internal\Pattern;
use CleanRegex\Match\MatchPattern;
use PHPUnit\Framework\TestCase;

class MatchMatchesPatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldMatch()
    {
        // given
        $pattern = new MatchPattern(new Pattern('[A-Z]?[a-z]+'), 'Nice matching pattern');

        // when
        $result = $pattern->matches();

        // then
        $this->assertTrue($result, "Failed asserting that subject matches the pattern");
    }

    /**
     * @test
     */
    public function shouldNotMatch()
    {
        // given
        $pattern = new MatchPattern(new Pattern('[A-Z]?[a-z]+'), 'NOT MATCHING');

        // when
        $result = $pattern->matches();

        // then
        $this->assertFalse($result, "Failed asserting that subject DOES NOT match the pattern");
    }
}

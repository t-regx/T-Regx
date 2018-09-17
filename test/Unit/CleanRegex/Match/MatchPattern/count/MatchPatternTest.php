<?php
namespace Test\Unit\CleanRegex\Match\MatchPattern\count;

use CleanRegex\Internal\Pattern;
use CleanRegex\Match\MatchPattern;
use PHPUnit\Framework\TestCase;

class MatchPatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldCountMatches()
    {
        // given
        $pattern = $this->getMatchPattern("Nice matching pattern");

        // when
        $count = $pattern->count();

        // then
        $this->assertEquals(3, $count);
    }

    /**
     * @test
     */
    public function shouldCountForUnmatchedPattern()
    {
        // given
        $pattern = $this->getMatchPattern("NOT MATCHING");

        // when
        $count = $pattern->count();

        // then
        $this->assertEquals(0, $count);
    }

    private function getMatchPattern(string $subject): MatchPattern
    {
        return new MatchPattern(new Pattern("([A-Z])?[a-z']+"), $subject);
    }
}

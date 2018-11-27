<?php
namespace Test\Unit\TRegx\CleanRegex\Match\MatchPattern\count;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\InternalPattern as Pattern;
use TRegx\CleanRegex\Match\MatchPattern;

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
    public function shouldCountMatches_count()
    {
        // given
        $pattern = $this->getMatchPattern("Nice matching pattern");

        // when
        $count = count($pattern);

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
        return new MatchPattern(new Pattern('([A-Z])?[a-z]+'), $subject);
    }
}

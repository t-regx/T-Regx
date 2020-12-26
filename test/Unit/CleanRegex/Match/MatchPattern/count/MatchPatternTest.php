<?php
namespace Test\Unit\TRegx\CleanRegex\Match\MatchPattern\count;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\InternalPattern;
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
        $this->assertSame(3, $count);
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
        $this->assertSame(3, $count);
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
        $this->assertSame(0, $count);
    }

    private function getMatchPattern(string $subject): MatchPattern
    {
        return new MatchPattern(InternalPattern::standard('([A-Z])?[a-z]+'), $subject);
    }
}

<?php
namespace Test\Unit\TRegx\CleanRegex\Match\MatchPattern\nth;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Exception\NoSuchNthElementException;
use TRegx\CleanRegex\Internal\InternalPattern;
use TRegx\CleanRegex\Match\MatchPattern;

class MatchPatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldExceptionContainDetails()
    {
        // given
        $pattern = $this->getMatchPattern("([A-Z])?[a-z']+", "Nice matching pattern");

        try {
            // when
            $pattern->nth(4);
        } catch (NoSuchNthElementException $exception) {
            // then
            $this->assertSame(4, $exception->getIndex());
            $this->assertSame(3, $exception->getTotal());
            $this->assertSame('Expected to get the 4-nth match, but only 3 occurrences were matched', $exception->getMessage());
        }
    }

    private function getMatchPattern(string $pattern, string $subject): MatchPattern
    {
        return new MatchPattern(InternalPattern::standard($pattern), $subject);
    }
}

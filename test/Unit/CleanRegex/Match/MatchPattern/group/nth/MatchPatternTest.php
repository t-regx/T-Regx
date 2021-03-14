<?php
namespace Test\Unit\TRegx\CleanRegex\Match\MatchPattern\group\nth;

use PHPUnit\Framework\TestCase;
use Test\Utils\Internal;
use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\CleanRegex\Exception\NonexistentGroupException;
use TRegx\CleanRegex\Exception\NoSuchNthElementException;
use TRegx\CleanRegex\Exception\SubjectNotMatchedException;
use TRegx\CleanRegex\Match\MatchPattern;

class MatchPatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldThrow_nth_forMissingGroup_forNegativeArgument()
    {
        // given
        $match = $this->getMatchPattern('Foo', 'Bar');

        // then
        $this->expectException(NonexistentGroupException::class);
        $this->expectExceptionMessage("Nonexistent group: 'unknown'");

        // when
        $match->group('unknown')->nth(-5);
    }

    /**
     * @test
     */
    public function shouldThrow_nth_forSubjectNotMatched()
    {
        // given
        $match = $this->getMatchPattern('F(oo)', 'Bar');

        // then
        $this->expectException(SubjectNotMatchedException::class);
        $this->expectExceptionMessage("Expected to get group #1 from the 4-nth match, but the subject was not matched");

        // when
        $match->group(1)->nth(4);
    }

    /**
     * @test
     */
    public function shouldThrow_nth_forUnmatchedGroup_forNegativeArgument()
    {
        // given
        $match = $this->getMatchPattern('foo:(?<unmatched>\d+)?', 'foo:1 foo:');

        // then
        $this->expectException(GroupNotMatchedException::class);
        $this->expectExceptionMessage("Expected to get group 'unmatched' from the 1-nth match, but the group was not matched");

        // when
        $match->group('unmatched')->nth(1);
    }

    /**
     * @test
     */
    public function shouldThrow_nth_forOverflowingNth()
    {
        // given
        $match = $this->getMatchPattern('foo:(\d+)', 'foo:1 foo:2 foo:3');
        // then
        $this->expectException(NoSuchNthElementException::class);
        $this->expectExceptionMessage("Expected to get group #1 from the 4-nth match, but only 3 occurrences were matched");

        // when
        $match->group(1)->nth(4);
    }

    private function getMatchPattern(string $pattern, string $subject): MatchPattern
    {
        return new MatchPattern(Internal::pattern($pattern), $subject);
    }
}

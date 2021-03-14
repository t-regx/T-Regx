<?php
namespace Test\Unit\TRegx\CleanRegex\Match\MatchPattern\offsets\fluent\all;

use PHPUnit\Framework\TestCase;
use Test\Utils\Internal;
use TRegx\CleanRegex\Exception\NonexistentGroupException;
use TRegx\CleanRegex\Match\MatchPattern;

class MatchPatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldReturn_offsets_fluent_all()
    {
        // given
        $pattern = $this->getMatchPattern('__ Nice matching pattern');

        // when
        $first = $pattern->offsets()->fluent()->all();

        // then
        $this->assertSame([3, 8, 17], $first);
    }

    /**
     * @test
     */
    public function shouldReturn_offsets_fluent_all_onUnmatchedSubject()
    {
        // given
        $pattern = $this->getMatchPattern('NOT MATCHING');

        // when
        $first = $pattern->offsets()->fluent()->all();

        // then
        $this->assertSame([], $first);
    }

    /**
     * @test
     */
    public function shouldThrow_offsets_fluent_all_onMissingGroup()
    {
        // given
        $pattern = $this->getMatchPattern('foo bar');

        // when
        $result = $pattern->group(1)->offsets()->fluent()->all();

        // then
        $this->assertSame([null, null], $result);
    }

    /**
     * @test
     */
    public function shouldThrow_offsets_fluent_all_onNonexistentGroup()
    {
        // given
        $pattern = $this->getMatchPattern('foo bar');

        // then
        $this->expectException(NonexistentGroupException::class);
        $this->expectExceptionMessage('Nonexistent group: #2');

        // when
        $pattern->group(2)->offsets()->fluent()->all();
    }

    private function getMatchPattern($subject): MatchPattern
    {
        return new MatchPattern(Internal::pattern("([A-Z])?[a-z']+"), $subject);
    }
}

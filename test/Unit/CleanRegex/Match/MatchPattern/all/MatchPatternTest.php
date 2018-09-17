<?php
namespace Test\Unit\CleanRegex\Match\MatchPattern\all;

use CleanRegex\Internal\Pattern;
use CleanRegex\Match\MatchPattern;
use PHPUnit\Framework\TestCase;

class MatchPatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetAll()
    {
        // given
        $pattern = new MatchPattern(new Pattern('([A-Z])?[a-z]+'), 'Nice matching pattern');

        // when
        $first = $pattern->all();

        // then
        $this->assertEquals(['Nice', 'matching', 'pattern'], $first);
    }

    /**
     * @test
     */
    public function shouldReturnEmptyArray_onNoMatches()
    {
        // given
        $pattern = new MatchPattern(new Pattern('([A-Z])?[a-z]+'), 'NOT MATCHING');

        // when
        $all = $pattern->all();

        // then
        $this->assertEquals([], $all, 'Failed asserting that all() returned an empty array');
    }
}

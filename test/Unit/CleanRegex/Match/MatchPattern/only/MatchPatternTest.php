<?php
namespace Test\Unit\CleanRegex\Match\MatchPattern\only;

use CleanRegex\Internal\Pattern;
use CleanRegex\Match\MatchPattern;
use InvalidArgumentException;
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
        $first = $pattern->only(2);

        // then
        $this->assertEquals(['Nice', 'matching'], $first);
    }

    /**
     * @test
     */
    public function shouldReturnEmptyArray_onNoMatches()
    {
        // given
        $pattern = new MatchPattern(new Pattern('([A-Z])?[a-z]+'), 'NOT MATCHING');

        // when
        $all = $pattern->only(2);

        // then
        $this->assertEquals([], $all, 'Failed asserting that all() returned an empty array');
    }

    /**
     * @test
     */
    public function shouldThrow_onNegativeLimit()
    {
        // given
        $pattern = new MatchPattern(new Pattern('([A-Z])?[a-z]+'), 'NOT MATCHING');

        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Negative limit -2');

        // when
        $pattern->only(-2);
    }
}

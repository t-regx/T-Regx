<?php
namespace Test\Unit\TRegx\CleanRegex\Match\MatchPattern\offsets\all;

use PHPUnit\Framework\TestCase;
use Test\Utils\Internal;
use TRegx\CleanRegex\Match\MatchPattern;

class MatchPatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetAll()
    {
        // given
        $pattern = new MatchPattern(Internal::pattern('([A-Z])?[a-z]+'), '__ Nice matching pattern');

        // when
        $first = $pattern->offsets()->all();

        // then
        $this->assertSame([3, 8, 17], $first);
    }

    /**
     * @test
     */
    public function shouldReturnEmptyArray_onNoMatches()
    {
        // given
        $pattern = new MatchPattern(Internal::pattern('([A-Z])?[a-z]+'), 'NOT MATCHING');

        // when
        $offsets = $pattern->offsets()->all();

        // then
        $this->assertEmpty($offsets, 'Failed asserting that offsets() returned an empty array');
    }
}

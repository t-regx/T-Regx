<?php
namespace Test\Unit\CleanRegex\Match\MatchPattern\groups\only;

use CleanRegex\Exception\CleanRegex\NonexistentGroupException;
use CleanRegex\Internal\Pattern;
use CleanRegex\Match\MatchPattern;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class MatchPatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetGroups()
    {
        // given
        $pattern = new MatchPattern(new Pattern('(?<two>[A-Z][a-z])?(?<rest>[a-z]+)'), 'Nice Matching Pattern');

        // when
        $twoGroups = $pattern->group('two')->only(2);
        $restGroups = $pattern->group('rest')->only(2);

        // then
        $this->assertEquals(['Ni', 'Ma'], $twoGroups);
        $this->assertEquals(['ce', 'tching'], $restGroups);
    }

    /**
     * @test
     */
    public function shouldReturnUnmatchedGroups()
    {
        // given
        $pattern = new MatchPattern(new Pattern('(?<hour>\d\d)?:(?<minute>\d\d)?'), 'First->11:__   Second->__:12   Third->13:32');

        // when
        $hours = $pattern->group('hour')->only(2);
        $minutes = $pattern->group('minute')->only(2);

        // then
        $this->assertEquals(['11', null], $hours);
        $this->assertEquals([null, '12'], $minutes);
    }

    /**
     * @test
     */
    public function shouldReturnEmptyArray_onNotMatchedSubject()
    {
        // given
        $pattern = new MatchPattern(new Pattern('(?<two>[A-Z][a-z])?(?<rest>[a-z]+)'), 'NOT MATCHING');

        // when
        $groups = $pattern->group('two')->only(2);

        // then
        $this->assertEquals([], $groups);
    }

    /**
     * @test
     */
    public function shouldThrow_onNonExistentGroup()
    {
        // given
        $pattern = new MatchPattern(new Pattern('(?<existing>[a-z]+)'), 'matching');

        // then
        $this->expectException(NonexistentGroupException::class);
        $this->expectExceptionMessage("Nonexistent group: missing");

        // when
        $pattern->group('missing')->only(2);
    }
}

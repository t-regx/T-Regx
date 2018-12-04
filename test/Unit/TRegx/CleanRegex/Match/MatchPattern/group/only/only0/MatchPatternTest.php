<?php
namespace Test\Unit\TRegx\CleanRegex\Match\MatchPattern\group\only\only0;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Exception\CleanRegex\NonexistentGroupException;
use TRegx\CleanRegex\Internal\InternalPattern as Pattern;
use TRegx\CleanRegex\Match\MatchPattern;

class MatchPatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGet_groups()
    {
        // given
        $pattern = new MatchPattern(new Pattern('(?<two>[A-Z][a-z])?(?<rest>[a-z]+)'), 'Nice Matching Pattern');

        // when
        $twoGroups = $pattern->group('two')->only(0);
        $restGroups = $pattern->group('rest')->only(0);

        // then
        $this->assertEquals([], $twoGroups);
        $this->assertEquals([], $restGroups);
    }

    /**
     * @test
     */
    public function shouldGet_unmatchedGroups()
    {
        // given
        $pattern = new MatchPattern(new Pattern('(?<hour>\d\d)?:(?<minute>\d\d)?'), 'First->11:__   Second->__:12   Third->13:32');

        // when
        $hours = $pattern->group('hour')->only(0);
        $minutes = $pattern->group('minute')->only(0);

        // then
        $this->assertEquals([], $hours);
        $this->assertEquals([], $minutes);
    }

    /**
     * @test
     */
    public function shouldReturnEmptyArray_onNotMatchedSubject()
    {
        // given
        $pattern = new MatchPattern(new Pattern('(?<two>[A-Z][a-z])?(?<rest>[a-z]+)'), 'NOT MATCHING');

        // when
        $groups = $pattern->group('two')->only(0);

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
        $this->expectExceptionMessage("Nonexistent group: 'missing'");

        // when
        $pattern->group('missing')->only(0);
    }

    /**
     * @test
     */
    public function shouldThrow_onNonExistentGroup_onNotMatchedSubject()
    {
        // given
        $pattern = new MatchPattern(new Pattern('(?<existing>[a-z]+)'), 'NOT MATCHING');

        // then
        $this->expectException(NonexistentGroupException::class);
        $this->expectExceptionMessage("Nonexistent group: 'missing'");

        // when
        $pattern->group('missing')->only(0);
    }

    /**
     * @test
     */
    public function shouldThrow_onInvalidGroupName()
    {
        // given
        $pattern = new MatchPattern(new Pattern('(?<existing>[a-z]+)'), 'matching');

        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Group name must be an alphanumeric string starting with a letter, given: '2invalid'");

        // when
        $pattern->group('2invalid')->only(0);
    }
}

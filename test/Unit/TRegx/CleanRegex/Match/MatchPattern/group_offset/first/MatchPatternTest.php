<?php
namespace Test\Unit\TRegx\CleanRegex\Match\MatchPattern\group_offset\first;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\CleanRegex\Exception\NonexistentGroupException;
use TRegx\CleanRegex\Exception\SubjectNotMatchedException;
use TRegx\CleanRegex\Internal\InternalPattern;
use TRegx\CleanRegex\Match\MatchPattern;

class MatchPatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGet_groups()
    {
        // given
        $pattern = new MatchPattern(InternalPattern::standard('(?<two>[A-Z][a-z])?(?<rest>[a-z]+)'), 'Nice Matching Pattern');

        // when
        $twoGroups = $pattern->group('two')->offsets()->first();
        $restGroups = $pattern->group('rest')->offsets()->first();

        // then
        $this->assertEquals(0, $twoGroups);
        $this->assertEquals(2, $restGroups);
    }

    /**
     * @test
     */
    public function shouldThrow_onNotMatchedSubject()
    {
        // given
        $pattern = new MatchPattern(InternalPattern::standard('(?<two>[A-Z][a-z])?(?<rest>[a-z]+)'), 'NOT MATCHING');

        // then
        $this->expectException(SubjectNotMatchedException::class);
        $this->expectExceptionMessage("Expected to get group 'two' offset from the first match, but subject was not matched at all");

        // when
        $pattern->group('two')->offsets()->first();
    }

    /**
     * @test
     */
    public function shouldThrow_onNotMatchedGroup()
    {
        // given
        $pattern = new MatchPattern(InternalPattern::standard('(?<unmatched>not this time)? [a-z]+'), ' matching');

        // then
        $this->expectException(GroupNotMatchedException::class);
        $this->expectExceptionMessage("Expected to get group 'unmatched' from the first match, but the group was not matched");

        // when
        $pattern->group('unmatched')->offsets()->first();
    }

    /**
     * @test
     */
    public function shouldThrow_onNonExistentGroup()
    {
        // given
        $pattern = new MatchPattern(InternalPattern::standard('(?<existing>[a-z]+)'), 'matching');

        // then
        $this->expectException(NonexistentGroupException::class);
        $this->expectExceptionMessage("Nonexistent group: 'missing'");

        // when
        $pattern->group('missing')->offsets()->first();
    }

    /**
     * @test
     */
    public function shouldThrow_onNonExistentGroup_onNotMatchedSubject()
    {
        // given
        $pattern = new MatchPattern(InternalPattern::standard('(?<existing>[a-z]+)'), 'NOT MATCHING');

        // then
        $this->expectException(NonexistentGroupException::class);
        $this->expectExceptionMessage("Nonexistent group: 'missing'");

        // when
        $pattern->group('missing')->offsets()->first();
    }

    /**
     * @test
     */
    public function shouldThrow_onInvalidGroupName()
    {
        // given
        $pattern = new MatchPattern(InternalPattern::standard('(?<existing>[a-z]+)'), 'matching');

        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Group name must be an alphanumeric string starting with a letter, given: '2invalid'");

        // when
        $pattern->group('2invalid')->offsets()->first();
    }
}

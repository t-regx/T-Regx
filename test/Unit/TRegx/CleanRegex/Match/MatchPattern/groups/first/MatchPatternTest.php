<?php
namespace Test\Unit\TRegx\CleanRegex\Match\MatchPattern\groups\first;

use TRegx\CleanRegex\Exception\CleanRegex\GroupNotMatchedException;
use TRegx\CleanRegex\Exception\CleanRegex\NonexistentGroupException;
use TRegx\CleanRegex\Exception\CleanRegex\SubjectNotMatchedException;
use TRegx\CleanRegex\Internal\InternalPattern as Pattern;
use TRegx\CleanRegex\Match\MatchPattern;
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
        $twoGroups = $pattern->group('two')->first();
        $restGroups = $pattern->group('rest')->first();

        // then
        $this->assertEquals('Ni', $twoGroups);
        $this->assertEquals('ce', $restGroups);
    }

    /**
     * @test
     */
    public function shouldThrow_onNotMatchedSubject()
    {
        // given
        $pattern = new MatchPattern(new Pattern('(?<two>[A-Z][a-z])?(?<rest>[a-z]+)'), 'NOT MATCHING');

        // then
        $this->expectException(SubjectNotMatchedException::class);
        $this->expectExceptionMessage(SubjectNotMatchedException::MESSAGE);

        // when
        $pattern->group('two')->first();
    }

    /**
     * @test
     */
    public function shouldThrow_onNotMatchedGroup()
    {
        // given
        $pattern = new MatchPattern(new Pattern('(?<unmatched>not this time)? (?<existing>[a-z]+)'), ' matching');

        // then
        $this->expectException(GroupNotMatchedException::class);
        $this->expectExceptionMessage(GroupNotMatchedException::MESSAGE);

        // when
        $pattern->group('unmatched')->first();
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
        $pattern->group('missing')->first();
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
        $this->expectExceptionMessage("Group name must be an alphanumeric string sequence starting with a letter, or an integer");

        // when
        $pattern->group('2invalid')->first();
    }
}

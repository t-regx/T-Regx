<?php
namespace Test\Unit\TRegx\CleanRegex\Match\MatchPattern\offsets\fluent;

use PHPUnit\Framework\TestCase;
use Test\Utils\Functions;
use Test\Utils\Internal;
use TRegx\CleanRegex\Exception\NonexistentGroupException;
use TRegx\CleanRegex\Exception\NoSuchElementFluentException;
use TRegx\CleanRegex\Match\MatchPattern;

class MatchPatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldReturn_offsets_fluent_findFirst_orElse_onNotMatchingSubject()
    {
        // given
        $pattern = $this->getMatchPattern('NOT MATCHING');

        // when
        $result = $pattern->offsets()->fluent()->findFirst(Functions::identity())->orElse(Functions::constant('replacement'));

        // then
        $this->assertSame('replacement', $result);
    }

    /**
     * @test
     */
    public function shouldThrow_offsets_fluent_first_forNonexistentGroup()
    {
        // given
        $pattern = $this->getMatchPattern('NOT MATCHING');

        // then
        $this->expectException(NonexistentGroupException::class);
        $this->expectExceptionMessage("Nonexistent group: 'missing'");

        // when
        $pattern->group('missing')->offsets()->fluent()->first();
    }

    /**
     * @test
     */
    public function shouldThrow_offsets_fluent_map_first_onUnmatchedGroup()
    {
        // given
        $pattern = $this->getMatchPattern('foo');

        // when
        $this->expectException(NoSuchElementFluentException::class);
        $this->expectExceptionMessage('Expected to get the first element from fluent pattern, but the elements feed has 0 element(s)');

        // when
        $pattern->group(1)->offsets()->fluent()->map(Functions::singleArg('strLen'))->first();
    }

    /**
     * @test
     */
    public function shouldThrow_offsets_fluent_keys_first_onUnmatchedGroup()
    {
        // given
        $pattern = $this->getMatchPattern('foo');

        // when
        $this->expectException(NoSuchElementFluentException::class);
        $this->expectExceptionMessage('Expected to get the first element from fluent pattern, but the elements feed has 0 element(s)');

        // when
        $pattern->group(1)->offsets()->fluent()->keys()->first();
    }

    /**
     * @test
     */
    public function shouldThrow_offsets_fluent_keys_first_onNonexistentGroup()
    {
        // given
        $pattern = $this->getMatchPattern('foo');

        // when
        $this->expectException(NonexistentGroupException::class);
        $this->expectExceptionMessage("Nonexistent group: 'missing'");

        // when
        $pattern->group('missing')->offsets()->fluent()->keys()->first();
    }

    private function getMatchPattern($subject): MatchPattern
    {
        return new MatchPattern(Internal::pattern("([A-Z])?([a-z']+)"), $subject);
    }
}

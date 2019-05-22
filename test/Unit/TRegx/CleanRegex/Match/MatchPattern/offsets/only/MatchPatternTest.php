<?php
namespace Test\Unit\TRegx\CleanRegex\Match\MatchPattern\offsets\only;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Test\Utils\PhpVersionDependent;
use TRegx\CleanRegex\Internal\InternalPattern as Pattern;
use TRegx\CleanRegex\Match\MatchPattern;
use TRegx\SafeRegex\Exception\CompileSafeRegexException;

class MatchPatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetAll()
    {
        // given
        $pattern = new MatchPattern(new Pattern('([A-Z])?[a-z]+'), '_ Nice matching pattern');

        // when
        $only = $pattern->offsets()->only(2);

        // then
        $this->assertEquals([2, 7], $only);
    }

    /**
     * @test
     */
    public function shouldReturnEmptyArray_onNoMatches()
    {
        // given
        $pattern = new MatchPattern(new Pattern('([A-Z])?[a-z]+'), 'NOT MATCHING');

        // when
        $only = $pattern->offsets()->only(2);

        // then
        $this->assertEquals([], $only, 'Failed asserting that only() returned an empty array');
    }

    /**
     * @test
     */
    public function shouldReturnEmptyArray_onNoMatches_onlyOne()
    {
        // given
        $pattern = new MatchPattern(new Pattern('([A-Z])?[a-z]+'), 'NOT MATCHING');

        // when
        $only = $pattern->offsets()->only(1);

        // then
        $this->assertEquals([], $only, 'Failed asserting that only() returned an empty array');
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
        $pattern->offsets()->only(-2);
    }

    /**
     * @test
     */
    public function shouldGetOne_withPregMatch()
    {
        // given
        $pattern = new MatchPattern(new Pattern('(?<group>[A-Z])?(?<group2>[a-z]+)'), '__ Nice matching pattern');

        // when
        $only = $pattern->offsets()->only(1);

        // then
        $this->assertEquals([3], $only);
    }

    /**
     * @test
     */
    public function shouldGetNone()
    {
        // given
        $pattern = new MatchPattern(new Pattern('(?<group>[A-Z])?(?<group2>[a-z]+)'), 'Nice matching pattern');

        // when
        $only = $pattern->offsets()->only(0);

        // then
        $this->assertEquals([], $only);
    }

    /**
     * @test
     */
    public function shouldValidatePattern_onOnly0()
    {
        // given
        $pattern = new MatchPattern(new Pattern('invalid)'), 'Nice matching pattern');

        // then
        $this->expectException(CompileSafeRegexException::class);
        $this->expectExceptionMessageRegExp(PhpVersionDependent::getUnmatchedParenthesisMessage(7));

        // when
        $pattern->offsets()->only(0);
    }
}

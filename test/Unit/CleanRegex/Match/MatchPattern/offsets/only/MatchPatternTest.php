<?php
namespace Test\Unit\TRegx\CleanRegex\Match\MatchPattern\offsets\only;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Test\PhpunitPolyfill;
use Test\Utils\Internal;
use Test\Utils\PhpVersionDependent;
use TRegx\CleanRegex\Match\MatchPattern;
use TRegx\Exception\MalformedPatternException;

class MatchPatternTest extends TestCase
{
    use PhpunitPolyfill;

    /**
     * @test
     */
    public function shouldGetAll()
    {
        // given
        $pattern = new MatchPattern(Internal::pattern('([A-Z])?[a-z]+'), '_ Nice matching pattern');

        // when
        $only = $pattern->offsets()->only(2);

        // then
        $this->assertSame([2, 7], $only);
    }

    /**
     * @test
     */
    public function shouldReturnEmptyArray_onNoMatches()
    {
        // given
        $pattern = new MatchPattern(Internal::pattern('([A-Z])?[a-z]+'), 'NOT MATCHING');

        // when
        $only = $pattern->offsets()->only(2);

        // then
        $this->assertEmpty($only, 'Failed asserting that only() returned an empty array');
    }

    /**
     * @test
     */
    public function shouldReturnEmptyArray_onNoMatches_onlyOne()
    {
        // given
        $pattern = new MatchPattern(Internal::pattern('([A-Z])?[a-z]+'), 'NOT MATCHING');

        // when
        $only = $pattern->offsets()->only(1);

        // then
        $this->assertEmpty($only, 'Failed asserting that only() returned an empty array');
    }

    /**
     * @test
     */
    public function shouldThrow_onNegativeLimit()
    {
        // given
        $pattern = new MatchPattern(Internal::pattern('([A-Z])?[a-z]+'), 'NOT MATCHING');

        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Negative limit: -2');

        // when
        $pattern->offsets()->only(-2);
    }

    /**
     * @test
     */
    public function shouldGetOne_withPregMatch()
    {
        // given
        $pattern = new MatchPattern(Internal::pattern('(?<group>[A-Z])?(?<group2>[a-z]+)'), '__ Nice matching pattern');

        // when
        $only = $pattern->offsets()->only(1);

        // then
        $this->assertSame([3], $only);
    }

    /**
     * @test
     */
    public function shouldGetNone()
    {
        // given
        $pattern = new MatchPattern(Internal::pattern('(?<group>[A-Z])?(?<group2>[a-z]+)'), 'Nice matching pattern');

        // when
        $only = $pattern->offsets()->only(0);

        // then
        $this->assertEmpty($only, "Failed asserting that only(0) returns an empty array");
    }

    /**
     * @test
     */
    public function shouldValidatePattern_onOnly0()
    {
        // given
        $pattern = new MatchPattern(Internal::pattern('invalid)'), 'Nice matching pattern');

        // then
        $this->expectException(MalformedPatternException::class);
        $this->expectExceptionMessageMatches(PhpVersionDependent::getUnmatchedParenthesisMessage(7));

        // when
        $pattern->offsets()->only(0);
    }
}

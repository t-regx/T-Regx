<?php
namespace Test\Unit\TRegx\CleanRegex\Match\MatchPattern\only;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Test\PhpunitPolyfill;
use Test\Utils\Definitions;
use Test\Fakes\CleanRegex\Internal\ThrowSubject;
use Test\Utils\PhpVersionDependent;
use TRegx\CleanRegex\Internal\StringSubject;
use TRegx\CleanRegex\Match\MatchPattern;
use TRegx\Exception\MalformedPatternException;

/**
 * @covers \TRegx\CleanRegex\Match\MatchPattern::only
 */
class MatchPatternTest extends TestCase
{
    use PhpunitPolyfill;

    /**
     * @test
     */
    public function shouldGetAll()
    {
        // given
        $pattern = new MatchPattern(Definitions::pattern('\w+'), new StringSubject('Nice matching pattern'));

        // when
        $only = $pattern->only(2);

        // then
        $this->assertSame(['Nice', 'matching'], $only);
    }

    /**
     * @test
     */
    public function shouldReturnEmptyArray_onNoMatches()
    {
        // given
        $pattern = new MatchPattern(Definitions::pattern('([A-Z])?[a-z]+'), new StringSubject('NOT MATCHING'));

        // when
        $only = $pattern->only(2);

        // then
        $this->assertEmpty($only, 'Failed asserting that only() returned an empty array');
    }

    /**
     * @test
     */
    public function shouldReturnEmptyArray_onNoMatches_onlyOne()
    {
        // given
        $pattern = new MatchPattern(Definitions::pattern('Foo'), new StringSubject('Bar'));

        // when
        $only = $pattern->only(1);

        // then
        $this->assertEmpty($only);
    }

    /**
     * @test
     */
    public function shouldThrow_onNegativeLimit()
    {
        // given
        $pattern = new MatchPattern(Definitions::pattern('Foo'), new ThrowSubject());

        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Negative limit: -2');

        // when
        $pattern->only(-2);
    }

    /**
     * @test
     */
    public function shouldGetOne_withPregMatch()
    {
        // given
        $pattern = new MatchPattern(Definitions::pattern('\w+'), new StringSubject('Nice matching pattern'));

        // when
        $only = $pattern->only(1);

        // then
        $this->assertSame(['Nice'], $only);
    }

    /**
     * @test
     */
    public function shouldGetNone()
    {
        // given
        $pattern = new MatchPattern(Definitions::pattern('Foo'), new ThrowSubject());

        // when
        $only = $pattern->only(0);

        // then
        $this->assertEmpty($only);
    }

    /**
     * @test
     */
    public function shouldValidatePattern_onOnly0()
    {
        // given
        $pattern = new MatchPattern(Definitions::pattern('invalid)'), new ThrowSubject());

        // then
        $this->expectException(MalformedPatternException::class);
        $this->expectExceptionMessageMatches(PhpVersionDependent::getUnmatchedParenthesisMessage(7));

        // when
        $pattern->only(0);
    }
}

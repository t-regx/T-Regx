<?php
namespace Test\Feature\CleanRegex\Match\only;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Test\Utils\Agnostic\PhpVersionDependent;
use Test\Utils\Assertion\AssertsDetail;
use Test\Utils\Backtrack\CausesBacktracking;
use Test\Utils\TestCase\TestCasePasses;
use TRegx\CleanRegex\Pattern;
use TRegx\Exception\MalformedPatternException;

/**
 * @covers \TRegx\CleanRegex\Match\Search
 */
class SearchTest extends TestCase
{
    use TestCasePasses, AssertsDetail, CausesBacktracking, PhpVersionDependent;

    /**
     * @test
     */
    public function shouldLimitTwo()
    {
        // given
        $pattern = Pattern::of('\w+')->search('Fili, Kili, Oin, Gloin');
        // when
        $only = $pattern->only(2);
        // then
        $this->assertSame(['Fili', 'Kili'], $only);
    }

    /**
     * @test
     */
    public function shouldLimitTwo_onUnmatchedSubject()
    {
        // given
        $pattern = Pattern::of('Foo')->search('Bar');
        // when
        $only = $pattern->only(2);
        // then
        $this->assertEmpty($only, 'Failed asserting that only() returned an empty array');
    }

    /**
     * @test
     */
    public function shouldLimitOne()
    {
        // given
        $pattern = Pattern::of('\w+')->search('Thorin, Thrain, Thror');
        // when
        $only = $pattern->only(1);
        // then
        $this->assertSame(['Thorin'], $only);
    }

    /**
     * @test
     */
    public function shouldLimitOne_PatternWithGroup()
    {
        // given
        $pattern = Pattern::of('((\w)\w+)')->search('Thorin, Thrain, Thror');
        // when
        $only = $pattern->only(1);
        // then
        $this->assertSame(['Thorin'], $only);
    }

    /**
     * @test
     */
    public function shouldLimitOne_onUnmatchedSubject()
    {
        // given
        $pattern = Pattern::of('Foo')->search('Bar');
        // when
        $only = $pattern->only(1);
        // then
        $this->assertEmpty($only);
    }

    /**
     * @test
     */
    public function shouldLimitZero()
    {
        // given
        $pattern = Pattern::of('Foo')->search('Foo');
        // when
        $only = $pattern->only(0);
        // then
        $this->assertSame([], $only);
    }

    /**
     * @test
     */
    public function shouldLimitZero_onUnmatchedSubject()
    {
        // given
        $pattern = Pattern::of('Foo')->search('Bar');
        // when
        $only = $pattern->only(0);
        // then
        $this->assertSame([], $only);
    }

    /**
     * @test
     */
    public function shouldThrow_onNegativeLimit()
    {
        // given
        $pattern = Pattern::of('Foo')->search('Bar');
        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Negative limit: -2');
        // when
        $pattern->only(-2);
    }

    /**
     * @test
     */
    public function shouldLimitTwo_validatePattern()
    {
        // given
        $match = Pattern::of('invalid)')->search('subject');
        // then
        $this->expectException(MalformedPatternException::class);
        $this->expectExceptionMessageMatches($this->unmatchedParenthesisMessage(7));
        // when
        $match->only(2);
    }

    /**
     * @test
     */
    public function shouldLimitZero_validatePattern()
    {
        // given
        $match = Pattern::of('invalid)')->search('subject');
        // then
        $this->expectException(MalformedPatternException::class);
        $this->expectExceptionMessageMatches($this->unmatchedParenthesisMessage(7));
        // when
        $match->only(0);
    }

    /**
     * @test
     */
    public function shouldLimitZero_backtrackingAtEdge()
    {
        // given
        $match = $this->backtrackingPattern()->search($this->backtrackingSubject(0));
        // when
        $match->only(0);
        // then
        $this->pass();
    }

    /**
     * @test
     */
    public function shouldLimitOne_backtrackingAtEdge()
    {
        // given
        $match = $this->backtrackingPattern()->search($this->backtrackingSubject(1));
        // when
        $match->only(1);
        // then
        $this->pass();
    }
}

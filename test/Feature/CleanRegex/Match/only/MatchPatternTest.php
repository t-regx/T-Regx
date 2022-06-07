<?php
namespace Test\Feature\CleanRegex\Match\only;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Test\Utils\Assertion\AssertsDetail;
use Test\Utils\Backtrack\CausesBacktracking;
use Test\Utils\TestCase\TestCasePasses;
use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\CleanRegex\Pattern;
use TRegx\Exception\MalformedPatternException;

/**
 * @covers \TRegx\CleanRegex\Match\MatchPattern
 * @covers \TRegx\CleanRegex\Internal\Match\MatchOnly
 */
class MatchPatternTest extends TestCase
{
    use TestCasePasses, AssertsDetail, CausesBacktracking;

    /**
     * @test
     */
    public function shouldLimitTwoDetails()
    {
        // given
        $pattern = Pattern::of('\w+')->match('Fili, Kili, Oin, Gloin');
        // when
        $only = $pattern->only(2);
        // then
        [$fili, $kili] = $only;
        $this->assertDetailText('Fili', $fili);
        $this->assertDetailText('Kili', $kili);
        $this->assertDetailsIndexed($fili, $kili);
    }

    /**
     * @test
     * @depends shouldLimitTwoDetails
     */
    public function shouldLimitTwo()
    {
        // given
        $pattern = Pattern::of('\w+')->match('Fili, Kili, Oin, Gloin');
        // when
        $only = $pattern->only(2);
        // then
        $this->assertSame(2, \count($only));
    }

    /**
     * @test
     */
    public function shouldLimitTwo_onUnmatchedSubject()
    {
        // given
        $pattern = Pattern::of('Foo')->match('Bar');
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
        $pattern = Pattern::of('\w+')->match('Thorin, Thrain, Thror');
        // when
        [$first] = $pattern->only(1);
        // then
        $this->assertDetailText('Thorin', $first);
        $this->assertDetailIndex(0, $first);
    }

    /**
     * @test
     */
    public function shouldLimitOne_getDetailWithGroupEmpty()
    {
        // given
        $pattern = Pattern::of('Foo()')->match('Foo');
        // when
        [$first] = $pattern->only(1);
        // then
        $this->assertSame('', $first->get(1));
    }

    /**
     * @test
     */
    public function shouldLimitOne_getDetailWithGroupUnmatched()
    {
        // given
        $pattern = Pattern::of('Foo(Bar)?')->match('Foo');
        [$first] = $pattern->only(1);
        // then
        $this->expectException(GroupNotMatchedException::class);
        $this->expectExceptionMessage('Expected to get group #1, but the group was not matched');
        // when
        $first->get(1);
    }

    /**
     * @test
     */
    public function shouldLimitOne_onUnmatchedSubject()
    {
        // given
        $pattern = Pattern::of('Foo')->match('Bar');
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
        $pattern = Pattern::of('Foo')->match('Foo');
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
        $pattern = Pattern::of('Foo')->match('Bar');
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
        $pattern = Pattern::of('Foo')->match('Bar');
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
        $match = Pattern::of('^+')->match('subject');
        // then
        $this->expectException(MalformedPatternException::class);
        $this->expectExceptionMessage('Quantifier does not follow a repeatable item at offset 1');
        // when
        $match->only(2);
    }

    /**
     * @test
     */
    public function shouldLimitZero_validatePattern()
    {
        // given
        $match = Pattern::of('+')->match('subject');
        // then
        $this->expectException(MalformedPatternException::class);
        $this->expectExceptionMessage('Quantifier does not follow a repeatable item at offset 0');
        // when
        $match->only(0);
    }

    /**
     * @test
     */
    public function shouldLimitZero_backtrackingAtEdge()
    {
        // given
        $match = $this->backtrackingPattern()->match($this->backtrackingSubject(0));
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
        $match = $this->backtrackingPattern()->match($this->backtrackingSubject(1));
        // when
        $match->only(1);
        // then
        $this->pass();
    }
}

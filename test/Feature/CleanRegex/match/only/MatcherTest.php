<?php
namespace Test\Feature\CleanRegex\match\only;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Test\Utils\Assertion\AssertsDetail;
use Test\Utils\Backtrack\CausesBacktracking;
use Test\Utils\TestCase\TestCasePasses;
use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\CleanRegex\Pattern;
use TRegx\Exception\MalformedPatternException;

/**
 * @covers \TRegx\CleanRegex\Match\Matcher
 * @covers \TRegx\CleanRegex\Internal\Match\MatchOnly
 */
class MatcherTest extends TestCase
{
    use TestCasePasses, AssertsDetail, CausesBacktracking;

    /**
     * @test
     */
    public function shouldLimitTwoDetails()
    {
        // given
        $matcher = Pattern::of('\w+')->match('Fili, Kili, Oin, Gloin');
        // when
        [$fili, $kili] = $matcher->only(2);
        // then
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
        $matcher = Pattern::of('\w+')->match('Fili, Kili, Oin, Gloin');
        // when
        $only = $matcher->only(2);
        // then
        $this->assertSame(2, \count($only));
    }

    /**
     * @test
     */
    public function shouldLimitTwo_onUnmatchedSubject()
    {
        // given
        $matcher = Pattern::of('Foo')->match('Bar');
        // when
        $only = $matcher->only(2);
        // then
        $this->assertEmpty($only, 'Failed asserting that only() returned an empty array');
    }

    /**
     * @test
     */
    public function shouldLimitOne()
    {
        // given
        $matcher = Pattern::of('\w+')->match('Thorin, Thrain, Thror');
        // when
        [$first] = $matcher->only(1);
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
        $matcher = Pattern::of('Foo()')->match('Foo');
        // when
        [$first] = $matcher->only(1);
        // then
        $this->assertSame('', $first->get(1));
    }

    /**
     * @test
     */
    public function shouldLimitOne_getDetailWithGroupUnmatched()
    {
        // given
        $matcher = Pattern::of('Foo(Bar)?')->match('Foo');
        [$first] = $matcher->only(1);
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
        $matcher = Pattern::of('Foo')->match('Bar');
        // when
        $only = $matcher->only(1);
        // then
        $this->assertEmpty($only);
    }

    /**
     * @test
     */
    public function shouldLimitZero()
    {
        // given
        $matcher = Pattern::of('Foo')->match('Foo');
        // when
        $only = $matcher->only(0);
        // then
        $this->assertSame([], $only);
    }

    /**
     * @test
     */
    public function shouldLimitZero_onUnmatchedSubject()
    {
        // given
        $matcher = Pattern::of('Foo')->match('Bar');
        // when
        $only = $matcher->only(0);
        // then
        $this->assertSame([], $only);
    }

    /**
     * @test
     */
    public function shouldThrow_onNegativeLimit()
    {
        // given
        $matcher = Pattern::of('Foo')->match('Bar');
        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Negative limit: -2');
        // when
        $matcher->only(-2);
    }

    /**
     * @test
     */
    public function shouldLimitTwo_validatePattern()
    {
        // given
        $matcher = Pattern::of('^+')->match('subject');
        // then
        $this->expectException(MalformedPatternException::class);
        $this->expectExceptionMessage('Quantifier does not follow a repeatable item at offset 1');
        // when
        $matcher->only(2);
    }

    /**
     * @test
     */
    public function shouldLimitZero_validatePattern()
    {
        // given
        $matcher = Pattern::of('+')->match('subject');
        // then
        $this->expectException(MalformedPatternException::class);
        $this->expectExceptionMessage('Quantifier does not follow a repeatable item at offset 0');
        // when
        $matcher->only(0);
    }

    /**
     * @test
     */
    public function shouldLimitZero_backtrackingAtEdge()
    {
        // given
        $matcher = $this->backtrackingPattern()->match($this->backtrackingSubject(0));
        // when
        $matcher->only(0);
        // then
        $this->pass();
    }

    /**
     * @test
     */
    public function shouldLimitOne_backtrackingAtEdge()
    {
        // given
        $matcher = $this->backtrackingPattern()->match($this->backtrackingSubject(1));
        // when
        $matcher->only(1);
        // then
        $this->pass();
    }
}

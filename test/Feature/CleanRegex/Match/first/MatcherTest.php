<?php
namespace Test\Feature\CleanRegex\Match\first;

use PHPUnit\Framework\TestCase;
use Test\Utils\Assertion\AssertsDetail;
use Test\Utils\Backtrack\CausesBacktracking;
use Test\Utils\TestCase\TestCasePasses;
use TRegx\CleanRegex\Exception\SubjectNotMatchedException;
use TRegx\CleanRegex\Pattern;
use TRegx\Exception\MalformedPatternException;

/**
 * @covers \TRegx\CleanRegex\Match\Matcher
 */
class MatcherTest extends TestCase
{
    use AssertsDetail, CausesBacktracking, TestCasePasses;

    /**
     * @test
     */
    public function shouldGetFirst()
    {
        // given
        $matcher = Pattern::of('\w+')->match('Socrates, Plato, Aristotle');
        // when
        $first = $matcher->first();
        // then
        $this->assertDetailText('Socrates', $first);
    }

    /**
     * @test
     */
    public function shouldGetFirst_emptyMatch()
    {
        // given
        $matcher = Pattern::of('()(?=ness)')->match('Emptyness');
        // when
        $detail = $matcher->first();
        // then
        $this->assertDetailText('', $detail);
        $this->assertDetailOffset(5, $detail);
    }

    /**
     * @test
     */
    public function shouldGetMatch_withDetails()
    {
        // given
        $matcher = Pattern::of("\w{2,}")->match('€ One, Two, Three');
        // when
        $detail = $matcher->first();
        // then
        $this->assertDetailText('One', $detail);
        $this->assertSame(0, $detail->index());
        $this->assertSame(['One', 'Two', 'Three'], $detail->all());
    }

    /**
     * @test
     */
    public function shouldThrow_onUnmatchedSubject()
    {
        // given
        $matcher = Pattern::of('Foo')->match('Bar');
        // then
        $this->expectException(SubjectNotMatchedException::class);
        $this->expectExceptionMessage('Expected to get the first match, but subject was not matched');
        // when
        $matcher->first();
    }

    /**
     * @test
     */
    public function shouldThrowSubjectNotMatched_getSubject()
    {
        // given
        $matcher = pattern('Foo')->match('Bar');
        try {
            // when
            $matcher->first();
        } catch (SubjectNotMatchedException $exception) {
            // then
            $this->assertSame('Bar', $exception->getSubject());
        }
    }

    /**
     * @test
     */
    public function shouldGetFirst_backtrackingAtEdge()
    {
        // given
        $matcher = $this->backtrackingPattern()->match($this->backtrackingSubject(1));
        // when
        $matcher->first();
        // then
        $this->pass();
    }

    /**
     * @test
     */
    public function shouldThrowForMalformedPattern()
    {
        // given
        $matcher = Pattern::of('+')->match('Bar');
        // then
        $this->expectException(MalformedPatternException::class);
        $this->expectExceptionMessage('Quantifier does not follow a repeatable item at offset 0');
        // when
        $matcher->first();
    }
}

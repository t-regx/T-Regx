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
 * @covers \TRegx\CleanRegex\Match\MatchPattern
 */
class MatchPatternTest extends TestCase
{
    use AssertsDetail, CausesBacktracking, TestCasePasses;

    /**
     * @test
     */
    public function shouldGetFirst()
    {
        // given
        $match = Pattern::of('\w+')->match('Socrates, Plato, Aristotle');
        // when
        $first = $match->first();
        // then
        $this->assertDetailText('Socrates', $first);
    }

    /**
     * @test
     */
    public function shouldGetFirst_emptyMatch()
    {
        // given
        $match = Pattern::of('()(?=ness)')->match('Emptyness');
        // when
        $detail = $match->first();
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
        $match = Pattern::of("\w{2,}")->match('€ One, Two, Three');
        // when
        $detail = $match->first();
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
        $match = Pattern::of('Foo')->match('Bar');
        // then
        $this->expectException(SubjectNotMatchedException::class);
        $this->expectExceptionMessage('Expected to get the first match, but subject was not matched');
        // when
        $match->first();
    }

    /**
     * @test
     */
    public function shouldThrowSubjectNotMatched_getSubject()
    {
        try {
            // when
            pattern('Foo')->match('Bar')->first();
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
        $match = $this->backtrackingPattern()->match($this->backtrackingSubject(1));
        // when
        $match->first();
        // then
        $this->pass();
    }

    /**
     * @test
     */
    public function shouldThrowForMalformedPattern()
    {
        // given
        $match = Pattern::of('+')->match('Bar');
        // then
        $this->expectException(MalformedPatternException::class);
        $this->expectExceptionMessage('Quantifier does not follow a repeatable item at offset 0');
        // when
        $match->first();
    }
}

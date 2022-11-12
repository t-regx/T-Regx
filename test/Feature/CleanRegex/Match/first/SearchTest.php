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
 * @covers \TRegx\CleanRegex\Match\Search
 */
class SearchTest extends TestCase
{
    use AssertsDetail, CausesBacktracking, TestCasePasses;

    /**
     * @test
     */
    public function shouldGetFirst()
    {
        // given
        $search = Pattern::of('\w+')->search('Socrates, Plato, Aristotle');
        // when
        $first = $search->first();
        // then
        $this->assertSame('Socrates', $first);
    }

    /**
     * @test
     */
    public function shouldGetFirst_emptyMatch()
    {
        // given
        $search = Pattern::of('()(?=ness)')->search('Emptyness');
        // when
        $text = $search->first();
        // then
        $this->assertSame('', $text);
    }

    /**
     * @test
     */
    public function shouldThrow_onUnmatchedSubject()
    {
        // given
        $search = Pattern::of('Foo')->search('Bar');
        // then
        $this->expectException(SubjectNotMatchedException::class);
        $this->expectExceptionMessage('Expected to get the first match, but subject was not matched');
        // when
        $search->first();
    }

    /**
     * @test
     */
    public function shouldGetFirst_backtrackingAtEdge()
    {
        // given
        $search = $this->backtrackingPattern()->search($this->backtrackingSubject(1));
        // when
        $search->first();
        // then
        $this->pass();
    }

    /**
     * @test
     */
    public function shouldThrowForMalformedPattern()
    {
        // given
        $match = Pattern::of('+')->search('Bar');
        // then
        $this->expectException(MalformedPatternException::class);
        $this->expectExceptionMessage('Quantifier does not follow a repeatable item at offset 0');
        // when
        $match->first();
    }
}

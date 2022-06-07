<?php
namespace Test\Feature\CleanRegex\Match\first;

use PHPUnit\Framework\TestCase;
use Test\Utils\Assertion\AssertsGroup;
use Test\Utils\Backtrack\CausesBacktracking;
use Test\Utils\Backtrack\ControledBacktracking;
use Test\Utils\DetailFunctions;
use Test\Utils\Functions;
use Test\Utils\TestCase\TestCasePasses;
use Test\Utils\Values\Definitions;
use TRegx\CleanRegex\Exception\SubjectNotMatchedException;
use TRegx\CleanRegex\Match\MatchPattern;
use TRegx\CleanRegex\Pattern;

/**
 * @covers \TRegx\CleanRegex\Match\MatchPattern
 */
class MatchPatternTest extends TestCase
{
    use AssertsGroup, CausesBacktracking, TestCasePasses;

    /**
     * @test
     */
    public function shouldGetFirst()
    {
        // given
        $pattern = $this->match('Nice matching pattern');
        // when
        $first = $pattern->first();
        // then
        $this->assertSame('Nice', $first->text());
    }

    /**
     * @test
     */
    public function shouldGetFirst_emptyMatch()
    {
        // given
        $match = Pattern::of('9?(?=matching)')->match('Nice matching pattern');
        // when
        $first = $match->first();
        // then
        $this->assertSame('', $first->text());
    }

    /**
     * @test
     */
    public function shouldGetMatch_withDetails()
    {
        // given
        $pattern = $this->match('Nice matching pattern');
        // when
        $detail = $pattern->first();
        // then
        $this->assertSame(0, $detail->index());
        $this->assertSame('Nice matching pattern', $detail->subject());
        $this->assertSame(['Nice', 'matching', 'pattern'], $detail->all());
        $this->assertGroupTexts(['N'], $detail->groups());
    }

    /**
     * @test
     */
    public function shouldNotInvokeFirst_onNotMatchingSubject()
    {
        // given
        $pattern = $this->match('NOT MATCHING');
        // then
        $this->expectException(SubjectNotMatchedException::class);
        $this->expectExceptionMessage('Expected to get the first match, but subject was not matched');
        // when
        $pattern->first();
    }

    /**
     * @test
     */
    public function shouldThrow_onNotMatchingSubject()
    {
        // given
        $pattern = $this->match('NOT MATCHING');
        // then
        $this->expectException(SubjectNotMatchedException::class);
        $this->expectExceptionMessage('Expected to get the first match, but subject was not matched');
        // when
        $pattern->first();
    }

    private function match(string $subject): MatchPattern
    {
        return Pattern::of("([A-Z])?[a-z]+")->match($subject);
    }

    /**
     * @test
     */
    public function shouldFirstGroupsNotCauseCatastrophicBacktracking()
    {
        // given
        $match = $this->backtrackingPattern()->match($this->backtrackingSubject());
        $detail = $match->first();
        // when
        $detail->groups();
        // then
        $this->pass();
    }

    /**
     * @test
     */
    public function shouldFirstNamedGroupsNotCauseCatastrophicBacktracking()
    {
        // given
        $match = $this->backtrackingPattern()->match($this->backtrackingSubject());
        $detail = $match->first();
        // when
        $detail->namedGroups();
        // then
        $this->pass();
    }

    /**
     * @test
     */
    public function shouldNth0GroupsNotCauseCatastrophicBacktracking()
    {
        // given
        $control = new ControledBacktracking();
        $detail = $control->match()->stream()->nth(0);
        // when
        $control->inStrictEnvironment(function () use ($detail) {
            // when
            $detail->groups();
            // then
            $this->pass();
        });
    }

    /**
     * @test
     */
    public function shouldNth0NamedGroupsNotCauseCatastrophicBacktracking()
    {
        // given
        $control = new ControledBacktracking();
        $detail = $control->match()->stream()->nth(0);
        // when
        $control->inStrictEnvironment(function () use ($detail) {
            // when
            $detail->namedGroups();
            // then
            $this->pass();
        });
    }
}

<?php
namespace Test\Feature\CleanRegex\Match\first;

use PHPUnit\Framework\TestCase;
use Test\Utils\AssertsGroup;
use Test\Utils\CausesBacktracking;
use Test\Utils\ControledBacktracking;
use Test\Utils\Definitions;
use Test\Utils\DetailFunctions;
use Test\Utils\Functions;
use Test\Utils\TestCasePasses;
use TRegx\CleanRegex\Exception\SubjectNotMatchedException;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\CleanRegex\Match\Details\Detail;
use TRegx\CleanRegex\Match\MatchPattern;
use TRegx\CleanRegex\Pattern;

/**
 * @covers \TRegx\CleanRegex\Match\MatchPattern::first
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
        $this->assertSame('Nice', $first);
    }

    /**
     * @test
     */
    public function shouldGetFirst_emptyMatch()
    {
        // given
        $pattern = new MatchPattern(Definitions::pattern("9?(?=matching)"), new Subject('Nice matching pattern'));
        // when
        $first = $pattern->first();
        // then
        $this->assertSame('', $first);
    }

    /**
     * @test
     */
    public function shouldGetFirst_withCallback()
    {
        // given
        $pattern = $this->match('Nice matching pattern');
        // when
        $first = $pattern->first('strRev');
        // then
        $this->assertSame('eciN', $first);
    }

    /**
     * @test
     */
    public function shouldGetMatch_withDetails()
    {
        // given
        $pattern = $this->match('Nice matching pattern');
        // when
        $pattern->first(function (Detail $detail) {
            // then
            $this->assertSame(0, $detail->index());
            $this->assertSame('Nice matching pattern', $detail->subject());
            $this->assertSame(['Nice', 'matching', 'pattern'], $detail->all());
            $this->assertGroupTexts(['N'], $detail->groups());
        });
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
        $pattern->first(Functions::fail());
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

    /**
     * @test
     */
    public function shouldThrow_withCallback_onNotMatchingSubject()
    {
        // given
        $pattern = $this->match('NOT MATCHING');
        // then
        $this->expectException(SubjectNotMatchedException::class);
        $this->expectExceptionMessage('Expected to get the first match, but subject was not matched');
        // when
        $pattern->first('strRev');
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
        $match->first(DetailFunctions::out($detail));
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
        $match->first(DetailFunctions::out($detail));
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

<?php
namespace Test\Unit\TRegx\CleanRegex\Match\MatchPattern\findFirst;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Test\Utils\Functions;
use TRegx\CleanRegex\Exception\SubjectNotMatchedException;
use TRegx\CleanRegex\Internal\InternalPattern;
use TRegx\CleanRegex\Match\Details\Match;
use TRegx\CleanRegex\Match\Details\NotMatched;
use TRegx\CleanRegex\Match\MatchPattern;

class MatchPatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetMatch_withDetails()
    {
        // given
        $pattern = $this->getMatchPattern("Nice matching pattern");

        // when
        $pattern
            ->findFirst(function (Match $match) {
                // then
                $this->assertEquals(0, $match->index());
                $this->assertEquals("Nice matching pattern", $match->subject());
                $this->assertEquals(['Nice', 'matching', 'pattern'], $match->all());
                $this->assertEquals(['N'], $match->groups()->texts());
            })
            ->orThrow();
    }

    /**
     * @test
     */
    public function shouldGetMatch_withoutCollapsingOrMethod()
    {
        // given
        $pattern = $this->getMatchPattern("Nice matching pattern");

        // when
        $pattern
            ->findFirst(function (Match $match) {
                // then
                $this->assertEquals("Nice matching pattern", $match->subject());
            });
        // ->orThrow();
    }

    /**
     * @test
     */
    public function shouldGetFirst()
    {
        // given
        $pattern = $this->getMatchPattern("Nice matching pattern");

        // when
        $first1 = $pattern->findFirst('strtoupper')->orReturn(null);
        $first2 = $pattern->findFirst('strtoupper')->orThrow();
        $first3 = $pattern->findFirst('strtoupper')->orElse(Functions::fail());

        // then
        $this->assertEquals('NICE', $first1);
        $this->assertEquals('NICE', $first2);
        $this->assertEquals('NICE', $first3);
    }

    /**
     * @test
     */
    public function shouldNotInvokeFirst_onNotMatchingSubject()
    {
        // given
        $pattern = $this->getMatchPattern('NOT MATCHING');

        // when
        $pattern->findFirst(Functions::fail())->orReturn(null);
        $pattern->findFirst(Functions::fail())->orElse(Functions::any());
        try {
            $pattern->findFirst(Functions::fail())->orThrow();
        } catch (SubjectNotMatchedException $ignored) {
        }

        // then
        $this->assertTrue(true);
    }

    /**
     * @test
     */
    public function should_onNotMatchingSubject_throw()
    {
        // given
        $pattern = $this->getMatchPattern('NOT MATCHING');

        // then
        $this->expectException(SubjectNotMatchedException::class);
        $this->expectExceptionMessage('Expected to get the first match, but subject was not matched');

        // when
        $pattern->findFirst('strrev')->orThrow();
    }

    /**
     * @test
     */
    public function should_onNotMatchingSubject_throw_userGivenException()
    {
        // given
        $pattern = $this->getMatchPattern('NOT MATCHING');

        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Expected to get the first match, but subject was not matched');

        // when
        $pattern->findFirst('strrev')->orThrow(InvalidArgumentException::class);
    }

    /**
     * @test
     */
    public function should_onNotMatchingSubject_throw_withMessage()
    {
        // given
        $pattern = $this->getMatchPattern('NOT MATCHING');

        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Expected to get the first match, but subject was not matched');

        // when
        $pattern->findFirst('strrev')->orThrow(InvalidArgumentException::class);
    }

    /**
     * @test
     */
    public function should_onNotMatchingSubject_getDefault()
    {
        // given
        $pattern = $this->getMatchPattern('NOT MATCHING');

        // when
        $value = $pattern->findFirst('strrev')->orReturn('def');

        // then
        $this->assertEquals('def', $value);
    }

    /**
     * @test
     */
    public function should_onNotMatchingSubject_call()
    {
        // given
        $pattern = $this->getMatchPattern('NOT MATCHING');

        // when
        $value = $pattern->findFirst('strrev')->orElse(Functions::constant('new value'));

        // then
        $this->assertEquals('new value', $value);
    }

    /**
     * @test
     */
    public function should_onNotMatchingSubject_call_withDetails()
    {
        // given
        $pattern = new MatchPattern(InternalPattern::standard("(?:[A-Z])?[a-z']+ (?<group>.)"), 'NOT MATCHING');

        // when
        $pattern->findFirst('strrev')->orElse(function (NotMatched $details) {
            // then
            $this->assertEquals('NOT MATCHING', $details->subject());
            $this->assertEquals(['group'], $details->groupNames());
            $this->assertTrue($details->hasGroup('group'));
            $this->assertTrue($details->hasGroup(0));
            $this->assertTrue($details->hasGroup(1));
            $this->assertFalse($details->hasGroup('other'));
            $this->assertFalse($details->hasGroup(2));
        });
    }

    private function getMatchPattern($subject): MatchPattern
    {
        return new MatchPattern(InternalPattern::standard("([A-Z])?[a-z']+"), $subject);
    }
}

<?php
namespace Test\Unit\TRegx\CleanRegex\Match\MatchPattern\first;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Exception\CleanRegex\SubjectNotMatchedException;
use TRegx\CleanRegex\Internal\InternalPattern as Pattern;
use TRegx\CleanRegex\Match\Details\Match;
use TRegx\CleanRegex\Match\MatchPattern;

class MatchPatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetFirst()
    {
        // given
        $pattern = $this->getMatchPattern('Nice matching pattern');

        // when
        $first = $pattern->first();

        // then
        $this->assertEquals('Nice', $first);
    }

    /**
     * @test
     */
    public function shouldGetFirst_emptyMatch()
    {
        // given
        $pattern = new MatchPattern(new Pattern("9?(?=matching)"), 'Nice matching pattern');

        // when
        $first = $pattern->first();

        // then
        $this->assertEquals('', $first);
    }

    /**
     * @test
     */
    public function shouldGetFirst_withCallback()
    {
        // given
        $pattern = $this->getMatchPattern('Nice matching pattern');

        // when
        $first = $pattern->first('strrev');

        // then
        $this->assertEquals('eciN', $first);
    }

    /**
     * @test
     */
    public function shouldGetMatch_withDetails()
    {
        // given
        $pattern = $this->getMatchPattern('Nice matching pattern');

        // when
        $pattern->first(function (Match $match) {
            // then
            $this->assertEquals(0, $match->index());
            $this->assertEquals('Nice matching pattern', $match->subject());
            $this->assertEquals(['Nice', 'matching', 'pattern'], $match->all());
            $this->assertEquals(['N'], $match->groups()->texts());
        });
    }

    /**
     * @test
     */
    public function shouldNotInvokeFirst_onNotMatchingSubject()
    {
        // given
        $pattern = $this->getMatchPattern('NOT MATCHING');

        try {
            // when
            $pattern->first(function () {
                // then
                $this->assertTrue(false, 'Failed asserting that first() is not invoked for not matching subject');
            });
        } catch (SubjectNotMatchedException $exception) {
            $this->assertTrue(true);
        }
    }

    /**
     * @test
     */
    public function shouldThrow_onNotMatchingSubject()
    {
        // given
        $pattern = $this->getMatchPattern('NOT MATCHING');

        // then
        $this->expectException(SubjectNotMatchedException::class);

        // when
        $pattern->first();
    }

    /**
     * @test
     */
    public function shouldThrow_withCallback_onNotMatchingSubject()
    {
        // given
        $pattern = $this->getMatchPattern('NOT MATCHING');

        // then
        $this->expectException(SubjectNotMatchedException::class);

        // when
        $pattern->first('strrev');
    }

    private function getMatchPattern($subject): MatchPattern
    {
        return new MatchPattern(new Pattern("([A-Z])?[a-z']+"), $subject);
    }
}

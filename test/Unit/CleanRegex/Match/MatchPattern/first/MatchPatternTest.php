<?php
namespace Test\Unit\CleanRegex\Match\MatchPattern\first;

use CleanRegex\Exception\CleanRegex\SubjectNotMatchedException;
use CleanRegex\Internal\Pattern;
use CleanRegex\Match\Details\Match;
use CleanRegex\Match\MatchPattern;
use PHPUnit\Framework\TestCase;

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
    public function shouldGetFirst_withCallback()
    {
        // given
        $pattern = $this->getMatchPattern("Nice matching pattern");

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
        $pattern = $this->getMatchPattern("Nice matching pattern");

        // when
        $pattern->first(function (Match $match) {

            // then
            $this->assertEquals(0, $match->index());
            $this->assertEquals("Nice matching pattern", $match->subject());
            $this->assertEquals(['Nice', 'matching', 'pattern'], $match->all());
            $this->assertEquals(['N'], $match->groups());
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
                $this->assertTrue(false, "Failed asserting that first() is not invoked for not matching subject");
            });
        } catch (SubjectNotMatchedException $exception) {
            // Ignore
        }
        $this->assertTrue(true);
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

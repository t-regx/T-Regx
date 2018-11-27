<?php
namespace Test\Unit\TRegx\CleanRegex\Match\MatchPattern\for_each;

use TRegx\CleanRegex\Internal\InternalPattern as Pattern;
use TRegx\CleanRegex\Match\Details\Match;
use TRegx\CleanRegex\Match\MatchPattern;
use PHPUnit\Framework\TestCase;

class MatchPatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetMatch_withDetails()
    {
        // given
        $pattern = $this->getMatchPattern("Nice matching pattern");
        $counter = 0;
        $matches = ['Nice', 'matching', 'pattern'];

        // when
        $pattern->forEach(function (Match $match) use (&$counter, $matches) {

            // then
            $this->assertEquals($matches[$counter], $match->text());
            $this->assertEquals($counter++, $match->index());
            $this->assertEquals('Nice matching pattern', $match->subject());
            $this->assertEquals(['Nice', 'matching', 'pattern'], $match->all());
        });
    }

    /**
     * @test
     */
    public function shouldNotInvokeCallback_onNotMatchingSubject()
    {
        // given
        $pattern = $this->getMatchPattern('NOT MATCHING');

        // when
        $pattern->forEach(function () {

            // then
            $this->assertTrue(false, 'Failed asserting that first() is not invoked for not matching subject');
        });

        // then
        $this->assertTrue(true);
    }

    private function getMatchPattern($subject): MatchPattern
    {
        return new MatchPattern(new Pattern("([A-Z])?[a-z']+"), $subject);
    }
}

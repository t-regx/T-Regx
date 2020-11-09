<?php
namespace Test\Unit\TRegx\CleanRegex\Match\MatchPattern\for_each;

use PHPUnit\Framework\TestCase;
use Test\Utils\Functions;
use TRegx\CleanRegex\Internal\InternalPattern;
use TRegx\CleanRegex\Match\Details\Detail;
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
        $counter = 0;
        $matches = ['Nice', 'matching', 'pattern'];

        // when
        $pattern->forEach(function (Detail $match) use (&$counter, $matches) {
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
        $pattern->forEach(Functions::fail());

        // then
        $this->assertTrue(true);
    }

    private function getMatchPattern($subject): MatchPattern
    {
        return new MatchPattern(InternalPattern::standard("([A-Z])?[a-z']+"), $subject);
    }
}

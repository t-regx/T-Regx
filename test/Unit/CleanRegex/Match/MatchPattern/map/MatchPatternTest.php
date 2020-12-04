<?php
namespace Test\Unit\TRegx\CleanRegex\Match\MatchPattern\map;

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
    public function shouldMap()
    {
        // given
        $pattern = $this->getMatchPattern("Nice matching pattern");

        // when
        $map = $pattern->map('strrev');

        // then
        $this->assertEquals(['eciN', 'gnihctam', 'nrettap'], $map);
    }

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
        $pattern->map(function (Detail $detail) use (&$counter, $matches) {
            // then
            $this->assertEquals($matches[$counter], $detail->text());
            $this->assertEquals($counter++, $detail->index());
            $this->assertEquals("Nice matching pattern", $detail->subject());
            $this->assertEquals($matches, $detail->all());
        });
    }

    /**
     * @test
     */
    public function shouldNotInvokeMap_onNotMatchingSubject()
    {
        // given
        $pattern = $this->getMatchPattern('NOT MATCHING');

        // when
        $pattern->map(Functions::fail());

        // then
        $this->assertTrue(true);
    }

    /**
     * @test
     */
    public function shouldReturnEmptyArray_onNoMatches()
    {
        // given
        $pattern = $this->getMatchPattern('NOT MATCHING');

        // when
        $map = $pattern->map(Functions::fail());

        // then
        $this->assertEquals([], $map, 'Failed asserting that map() returned an empty array');
    }

    private function getMatchPattern($subject): MatchPattern
    {
        return new MatchPattern(InternalPattern::standard("([A-Z])?[a-z']+"), $subject);
    }
}

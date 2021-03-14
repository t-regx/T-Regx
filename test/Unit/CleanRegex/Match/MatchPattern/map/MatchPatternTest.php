<?php
namespace Test\Unit\TRegx\CleanRegex\Match\MatchPattern\map;

use PHPUnit\Framework\TestCase;
use Test\Utils\Functions;
use Test\Utils\Internal;
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
        $map = $pattern->map('strToUpper');

        // then
        $this->assertSame(['NICE', 'MATCHING', 'PATTERN'], $map);
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
            $this->assertSame($matches[$counter], $detail->text());
            $this->assertSame($counter++, $detail->index());
            $this->assertSame("Nice matching pattern", $detail->subject());
            $this->assertSame($matches, $detail->all());
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
        $this->assertEmpty($map, 'Failed asserting that map() returned an empty array');
    }

    private function getMatchPattern($subject): MatchPattern
    {
        return new MatchPattern(Internal::pattern("([A-Z])?[a-z']+"), $subject);
    }
}

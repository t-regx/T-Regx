<?php
namespace Test\Feature\TRegx\CleanRegex\Match\map;

use PHPUnit\Framework\TestCase;
use Test\Utils\Functions;
use Test\Utils\TestCasePasses;
use TRegx\CleanRegex\Match\Details\Detail;
use TRegx\CleanRegex\Match\MatchPattern;
use TRegx\CleanRegex\Pattern;

/**
 * @covers \TRegx\CleanRegex\Match\MatchPattern::map
 */
class MatchPatternTest extends TestCase
{
    use TestCasePasses;

    /**
     * @test
     */
    public function shouldMap()
    {
        // given
        $pattern = $this->match("Nice matching pattern");
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
        $pattern = $this->match("Nice matching pattern");
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
        $pattern = $this->match('NOT MATCHING');
        // when
        $pattern->map(Functions::fail());
        // then
        $this->pass();
    }

    /**
     * @test
     */
    public function shouldReturnEmptyArray_onNoMatches()
    {
        // given
        $pattern = $this->match('NOT MATCHING');
        // when
        $map = $pattern->map(Functions::fail());
        // then
        $this->assertEmpty($map, 'Failed asserting that map() returned an empty array');
    }

    private function match(string $subject): MatchPattern
    {
        return Pattern::of("([A-Z])?[a-z']+")->match($subject);
    }
}

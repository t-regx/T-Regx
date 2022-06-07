<?php
namespace Test\Feature\CleanRegex\Match\map;

use PHPUnit\Framework\TestCase;
use Test\Utils\Functions;
use Test\Utils\TestCasePasses;
use TRegx\CleanRegex\Match\MatchPattern;
use TRegx\CleanRegex\Pattern;

/**
 * @covers \TRegx\CleanRegex\Match\MatchPattern
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
        $pattern = $this->match('Nice matching pattern');
        // when
        $pattern->map(Functions::collect($details));
        // then
        [$first, $second, $third] = $details;

        $this->assertSame(['Nice', 'matching', 'pattern'], ["$first", "$second", "$third"]);
        $this->assertSame([0, 1, 2], [$first->index(), $second->index(), $third->index()]);

        $this->assertSame('Nice matching pattern', $first->subject());
        $this->assertSame('Nice matching pattern', $second->subject());
        $this->assertSame('Nice matching pattern', $third->subject());

        $matches = ['Nice', 'matching', 'pattern'];
        $this->assertSame($matches, $first->all());
        $this->assertSame($matches, $second->all());
        $this->assertSame($matches, $third->all());
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

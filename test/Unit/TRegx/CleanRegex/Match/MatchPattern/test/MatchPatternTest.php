<?php
namespace Test\Unit\TRegx\CleanRegex\Match\MatchPattern\test;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\InternalPattern;
use TRegx\CleanRegex\Match\MatchPattern;

class MatchPatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldMatch()
    {
        // given
        $pattern = new MatchPattern(InternalPattern::standard('[A-Z]?[a-z]+'), 'Nice matching pattern');

        // when
        $result = $pattern->test();

        // then
        $this->assertTrue($result, "Failed asserting that subject matches the pattern");
    }

    /**
     * @test
     */
    public function shouldNotMatch()
    {
        // given
        $pattern = new MatchPattern(InternalPattern::standard('[A-Z]?[a-z]+'), 'NOT MATCHING');

        // when
        $result = $pattern->test();

        // then
        $this->assertFalse($result, "Failed asserting that subject DOES NOT match the pattern");
    }
}

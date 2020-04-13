<?php
namespace Test\Unit\TRegx\CleanRegex\Match\MatchPattern\fails;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\InternalPattern as Pattern;
use TRegx\CleanRegex\Match\MatchPattern;

class MatchPatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldMatch()
    {
        // given
        $pattern = new MatchPattern(Pattern::standard('[A-Z]?[a-z]+'), 'Nice matching pattern');

        // when
        $result = $pattern->fails();

        // then
        $this->assertFalse($result, "Failed asserting that subject fails the pattern");
    }

    /**
     * @test
     */
    public function shouldNotMatch()
    {
        // given
        $pattern = new MatchPattern(Pattern::standard('[A-Z]?[a-z]+'), 'NOT MATCHING');

        // when
        $result = $pattern->fails();

        // then
        $this->assertTrue($result, "Failed asserting that subject DOES NOT fail the pattern");
    }
}

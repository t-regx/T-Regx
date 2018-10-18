<?php
namespace Test\Unit\TRegx\CleanRegex\Match\MatchPattern\filter;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\InternalPattern as Pattern;
use TRegx\CleanRegex\Match\Details\Match;
use TRegx\CleanRegex\Match\MatchPattern;

class MatchPatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetAll()
    {
        // given
        $pattern = new MatchPattern(new Pattern('([A-Z])?[a-z]+'), 'Nice matching pattern');

        // when
        $first = $pattern
            ->filter(function (Match $match) {
                return strlen($match) > 4;
            })
            ->all();

        // then
        $this->assertEquals(['matching', 'pattern'], $first);
    }
}

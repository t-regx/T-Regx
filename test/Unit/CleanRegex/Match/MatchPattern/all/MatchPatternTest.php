<?php
namespace Test\Unit\TRegx\CleanRegex\Match\MatchPattern\all;

use PHPUnit\Framework\TestCase;
use Test\Utils\Internal;
use TRegx\CleanRegex\Match\MatchPattern;

class MatchPatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetAll()
    {
        // given
        $pattern = new MatchPattern(Internal::pattern('\w+'), 'Nice matching pattern');

        // when
        $all = $pattern->all();

        // then
        $this->assertSame(['Nice', 'matching', 'pattern'], $all);
    }

    /**
     * @test
     */
    public function shouldReturnEmptyArray_onNoMatches()
    {
        // given
        $pattern = new MatchPattern(Internal::pattern('Foo'), 'Bar');

        // when
        $all = $pattern->all();

        // then
        $this->assertEmpty($all, 'Failed asserting that all() returned an empty array');
    }
}

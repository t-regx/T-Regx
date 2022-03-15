<?php
namespace Test\Unit\TRegx\CleanRegex\Match\MatchPattern\all;

use PHPUnit\Framework\TestCase;
use Test\Utils\Definitions;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\CleanRegex\Match\MatchPattern;

/**
 * @covers \TRegx\CleanRegex\Match\MatchPattern::all
 */
class MatchPatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetAll()
    {
        // given
        $pattern = new MatchPattern(Definitions::pattern('\w+'), new Subject('Nice matching pattern'));

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
        $pattern = new MatchPattern(Definitions::pattern('Foo'), new Subject('Bar'));

        // when
        $all = $pattern->all();

        // then
        $this->assertEmpty($all, 'Failed asserting that all() returned an empty array');
    }
}

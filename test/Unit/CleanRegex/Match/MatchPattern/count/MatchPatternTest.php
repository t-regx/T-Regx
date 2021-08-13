<?php
namespace Test\Unit\TRegx\CleanRegex\Match\MatchPattern\count;

use PHPUnit\Framework\TestCase;
use Test\Utils\Internal;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\CleanRegex\Match\MatchPattern;

/**
 * @covers \TRegx\CleanRegex\Match\MatchPattern::count
 */
class MatchPatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldCount()
    {
        // given
        $pattern = new MatchPattern(Internal::pattern('Foo'), new Subject('Foo Foo Foo'));

        // when
        $count = $pattern->count();

        // then
        $this->assertSame(3, $count);
    }

    /**
     * @test
     */
    public function shouldBeCountable()
    {
        // given
        $pattern = new MatchPattern(Internal::pattern('Foo'), new Subject('Foo Foo Foo'));

        // when
        $count = count($pattern);

        // then
        $this->assertSame(3, $count);
    }

    /**
     * @test
     */
    public function shouldCountUnmatchedSubject()
    {
        // given
        $pattern = new MatchPattern(Internal::pattern('Foo'), new Subject('Bar'));

        // when
        $count = $pattern->count();

        // then
        $this->assertSame(0, $count);
    }
}

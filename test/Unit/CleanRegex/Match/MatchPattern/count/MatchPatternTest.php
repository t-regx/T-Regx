<?php
namespace Test\Unit\TRegx\CleanRegex\Match\MatchPattern\count;

use PHPUnit\Framework\TestCase;
use Test\Utils\Definitions;
use TRegx\CleanRegex\Internal\StringSubject;
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
        $pattern = new MatchPattern(Definitions::pattern('Foo'), new StringSubject('Foo Foo Foo'));

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
        $pattern = new MatchPattern(Definitions::pattern('Foo'), new StringSubject('Foo Foo Foo'));

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
        $pattern = new MatchPattern(Definitions::pattern('Foo'), new StringSubject('Bar'));

        // when
        $count = $pattern->count();

        // then
        $this->assertSame(0, $count);
    }
}

<?php
namespace Test\Unit\TRegx\CleanRegex\Match\MatchPattern\test;

use PHPUnit\Framework\TestCase;
use Test\Utils\Internal;
use TRegx\CleanRegex\Internal\StringSubject;
use TRegx\CleanRegex\Match\MatchPattern;

/**
 * @covers \TRegx\CleanRegex\Match\MatchPattern::test
 */
class MatchPatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldTest()
    {
        // given
        $pattern = new MatchPattern(Internal::pattern('Foo'), new StringSubject('Foo'));

        // when
        $test = $pattern->test();

        // then
        $this->assertTrue($test);
    }

    /**
     * @test
     */
    public function shouldNotTest()
    {
        // given
        $pattern = new MatchPattern(Internal::pattern('Foo'), new StringSubject('Bar'));

        // when
        $test = $pattern->test();

        // then
        $this->assertFalse($test);
    }
}

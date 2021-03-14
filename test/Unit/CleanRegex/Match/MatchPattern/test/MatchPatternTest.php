<?php
namespace Test\Unit\TRegx\CleanRegex\Match\MatchPattern\test;

use PHPUnit\Framework\TestCase;
use Test\Utils\Internal;
use TRegx\CleanRegex\Match\MatchPattern;

class MatchPatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldTest()
    {
        // given
        $pattern = new MatchPattern(Internal::pattern('Foo'), 'Foo');

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
        $pattern = new MatchPattern(Internal::pattern('Foo'), 'Bar');

        // when
        $test = $pattern->test();

        // then
        $this->assertFalse($test);
    }
}

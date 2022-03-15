<?php
namespace Test\Unit\TRegx\CleanRegex\Match\MatchPattern\test;

use PHPUnit\Framework\TestCase;
use Test\Utils\Definitions;
use TRegx\CleanRegex\Internal\Subject;
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
        $pattern = new MatchPattern(Definitions::pattern('Foo'), new Subject('Foo'));

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
        $pattern = new MatchPattern(Definitions::pattern('Foo'), new Subject('Bar'));

        // when
        $test = $pattern->test();

        // then
        $this->assertFalse($test);
    }
}

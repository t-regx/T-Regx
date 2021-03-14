<?php
namespace Test\Unit\TRegx\CleanRegex\Match\MatchPattern\fails;

use PHPUnit\Framework\TestCase;
use Test\Utils\Internal;
use TRegx\CleanRegex\Match\MatchPattern;

class MatchPatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldNotMatch()
    {
        // given
        $pattern = new MatchPattern(Internal::pattern('Foo'), 'Bar');

        // when
        $fails = $pattern->fails();

        // then
        $this->assertTrue($fails);
    }

    /**
     * @test
     */
    public function shouldNotFail()
    {
        // given
        $pattern = new MatchPattern(Internal::pattern('Foo'), 'Foo');

        // when
        $fails = $pattern->fails();

        // then
        $this->assertFalse($fails);
    }
}

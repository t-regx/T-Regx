<?php
namespace Test\Unit\TRegx\CleanRegex\Match\MatchPattern\fails;

use PHPUnit\Framework\TestCase;
use Test\Utils\Definitions;
use TRegx\CleanRegex\Internal\StringSubject;
use TRegx\CleanRegex\Match\MatchPattern;

/**
 * @covers \TRegx\CleanRegex\Match\MatchPattern::fails
 */
class MatchPatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldNotMatch()
    {
        // given
        $pattern = new MatchPattern(Definitions::pattern('Foo'), new StringSubject('Bar'));

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
        $pattern = new MatchPattern(Definitions::pattern('Foo'), new StringSubject('Foo'));

        // when
        $fails = $pattern->fails();

        // then
        $this->assertFalse($fails);
    }
}

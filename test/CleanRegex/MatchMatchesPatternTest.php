<?php
namespace Test\CleanRegex;

use CleanRegex\Internal\Pattern;
use CleanRegex\Match\MatchPattern;
use PHPUnit\Framework\TestCase;

class MatchMatchesPatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldMatchPattern()
    {
        // given
        $pattern = new MatchPattern(new Pattern('/[a-z]/'), 'welcome');

        // when
        $result = $pattern->matches();

        // then
        $this->assertTrue($result);
    }

    /**
     * @test
     */
    public function shouldNotMatchPattern()
    {
        // given
        $pattern = new MatchPattern(new Pattern('/^[a-z]+$/'), 'space space');

        // when
        $result = $pattern->matches();

        // then
        $this->assertFalse($result);
    }
}

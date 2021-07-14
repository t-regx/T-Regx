<?php
namespace Test\Feature\TRegx\CleanRegex\Builder\PatternBuilder\_extended;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Pattern;

/**
 * @coversNothing
 */
class PatternBuilderTest extends TestCase
{
    /**
     * @test
     */
    public function shouldBuild_newsLines()
    {
        // given
        $pattern = Pattern::inject("@:bar", ["user\ninput"]);

        // when
        $match = $pattern->match("user\ninput:bar")->first();

        // then
        $this->assertSame("user\ninput:bar", $match);
    }

    /**
     * @test
     */
    public function shouldBuild_newsLines_ExtendedMode()
    {
        // given
        $pattern = Pattern::inject("@:bar", ["user\ninput"], 'x');

        // when
        $match = $pattern->match("user\ninput:bar")->first();

        // then
        $this->assertSame("user\ninput:bar", $match);
    }
}

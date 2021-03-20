<?php
namespace Test\Feature\TRegx\CleanRegex\Builder\PatternBuilder\builder\_extended;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Pattern;

class PatternBuilderTest extends TestCase
{
    /**
     * @test
     */
    public function shouldBuild_newsLines()
    {
        // given
        $pattern = Pattern::inject("#@\npattern", ["user\ninput"]);

        // when
        $match = $pattern->match("#user\ninput\npattern")->first();

        // then
        $this->assertSame("#user\ninput\npattern", $match);
    }

    /**
     * @test
     */
    public function shouldBuild_newsLines_ExtendedMode()
    {
        // given
        $pattern = Pattern::inject("#@\npattern", ["user\ninput"], 'x');

        // when
        $match = $pattern->match("#user\ninput\npattern")->first();

        // then
        $this->assertSame('pattern', $match);
    }
}

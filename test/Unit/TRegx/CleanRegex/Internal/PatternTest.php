<?php
namespace Test\Unit\TRegx\CleanRegex\Internal;

use TRegx\CleanRegex\Internal\Pattern;
use PHPUnit\Framework\TestCase;

class PatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldDelimiterWithFlags()
    {
        // given
        $pattern = new Pattern('[a-z]+', 'mi');

        // when
        $delimitered = $pattern->pattern;

        // then
        $this->assertEquals('/[a-z]+/mi', $delimitered);
    }

    /**
     * @test
     */
    public function shouldKeepOriginalPattern()
    {
        // given
        $pattern = new Pattern('[a-z]+', 'mi');

        // when
        $original = $pattern->originalPattern;

        // then
        $this->assertEquals('[a-z]+', $original);
    }
}

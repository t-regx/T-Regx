<?php
namespace Test\Unit\TRegx\CleanRegex\Internal;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\InternalPattern as Pattern;

class InternalPatternTest extends TestCase
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

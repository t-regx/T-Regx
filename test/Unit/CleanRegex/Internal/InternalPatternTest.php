<?php
namespace Test\Unit\TRegx\CleanRegex\Internal;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\InternalPattern;

class InternalPatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldAutomaticallyDelimiter()
    {
        // given
        $pattern = InternalPattern::standard('[a-z]+', 'mi');

        // when + then
        $this->assertEquals('/[a-z]+/mi', $pattern->pattern);
        $this->assertEquals('[a-z]+', $pattern->originalPattern);
    }

    /**
     * @test
     */
    public function shouldKeepOriginalPattern()
    {
        // given
        $pattern = InternalPattern::standard('[a-z]+', 'mi');

        // when
        $original = $pattern->originalPattern;

        // then
        $this->assertEquals('[a-z]+', $original);
    }

    /**
     * @test
     */
    public function shouldCreateManual()
    {
        // given
        $pattern = InternalPattern::pcre('/[a-z]+/mi');

        // when + then
        $this->assertEquals('/[a-z]+/mi', $pattern->pattern);
        $this->assertEquals('/[a-z]+/mi', $pattern->originalPattern);
    }
}

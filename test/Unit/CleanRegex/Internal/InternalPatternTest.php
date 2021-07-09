<?php
namespace Test\Unit\TRegx\CleanRegex\Internal;

use PHPUnit\Framework\TestCase;
use Test\Utils\Internal;
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
        $this->assertSame('/[a-z]+/mi', $pattern->pattern);
        $this->assertSame('[a-z]+', $pattern->undevelopedInput);
    }

    /**
     * @test
     */
    public function shouldPreserveUndevelopedInput()
    {
        // given
        $pattern = InternalPattern::standard('[a-z]+', 'mi');

        // when
        $original = $pattern->undevelopedInput;

        // then
        $this->assertSame('[a-z]+', $original);
    }

    /**
     * @test
     */
    public function shouldCreateIdentity()
    {
        // given
        $pattern = Internal::pcre('/[a-z]+/mi');

        // when + then
        $this->assertSame('/[a-z]+/mi', $pattern->pattern);
        $this->assertSame('/[a-z]+/mi', $pattern->undevelopedInput);
    }
}

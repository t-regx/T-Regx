<?php
namespace Test\Unit\TRegx\CleanRegex\CompositePattern\anyMatches;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\CompositePattern;

class CompositePatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldMatch()
    {
        // given
        $pattern = CompositePattern::of([
            '/https?/i',
            'fail',
            '/failed/'
        ]);

        // when
        $match = $pattern->anyMatches("http");

        // then
        $this->assertTrue($match);
    }

    /**
     * @test
     */
    public function shouldNotMatch()
    {
        // given
        $pattern = CompositePattern::of([
            '/https?$/i',
            'fail',
            '/failed/'
        ]);

        // when
        $match = $pattern->anyMatches("httpz");

        // then
        $this->assertFalse($match);
    }
}

<?php
namespace Test\Unit\TRegx\CleanRegex\CompositePattern\anyMatches;

use TRegx\CleanRegex\CompositePattern;
use PHPUnit\Framework\TestCase;

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

    public function shouldNotMatch()
    {
        // given
        $pattern = CompositePattern::of([
            '/https?/i',
            'fail',
            '/failed/'
        ]);

        // when
        $match = $pattern->anyMatches("httpz");

        // then
        $this->assertTrue($match);
    }
}

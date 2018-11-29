<?php
namespace Test\Integration\TRegx\CleanRegex\CompositePattern\allMatch;

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
            '/^fro/i',
            'rod',
            'odo$'
        ]);

        // when
        $match = $pattern->allMatch("Frodo");

        // then
        $this->assertTrue($match);
    }

    public function shouldNotMatch()
    {
        // given
        $pattern = CompositePattern::of([
            '/^fro/i',
            'rod',
            'odo$'
        ]);

        // when
        $match = $pattern->allMatch("Frodo2");

        // then
        $this->assertFalse($match);
    }
}

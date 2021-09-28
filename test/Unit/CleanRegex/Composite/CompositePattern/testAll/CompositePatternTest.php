<?php
namespace Test\Unit\TRegx\CleanRegex\Composite\CompositePattern\testAll;

use PHPUnit\Framework\TestCase;
use Test\Utils\Definitions;
use TRegx\CleanRegex\Composite\CompositePattern;

/**
 * @covers \TRegx\CleanRegex\Composite\CompositePattern::testAll
 */
class CompositePatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldMatch()
    {
        // given
        $pattern = new CompositePattern($this->patterns());

        // when
        $match = $pattern->testAll('Frodo');

        // then
        $this->assertTrue($match);
    }

    /**
     * @test
     */
    public function shouldNotMatch()
    {
        // given
        $pattern = new CompositePattern($this->patterns());

        // when
        $match = $pattern->testAll('Frodo2');

        // then
        $this->assertFalse($match);
    }

    private function patterns(): array
    {
        return [
            Definitions::pcre('/^fro/i'),
            Definitions::pcre('/rod/'),
            Definitions::pcre('/odo$/')
        ];
    }
}

<?php
namespace Test\Unit\TRegx\CleanRegex\Composite\CompositePattern\anyMatches;

use PHPUnit\Framework\TestCase;
use Test\Utils\Definitions;
use TRegx\CleanRegex\Composite\CompositePattern;

/**
 * @covers \TRegx\CleanRegex\Composite\CompositePattern::anyMatches
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
        $matches = $pattern->anyMatches('http');

        // then
        $this->assertTrue($matches);
    }

    /**
     * @test
     */
    public function shouldNotMatch()
    {
        // given
        $pattern = new CompositePattern($this->patterns());

        // when
        $matches = $pattern->anyMatches('Foo');

        // then
        $this->assertFalse($matches);
    }

    private function patterns(): array
    {
        return [
            Definitions::pcre('/https?/i'),
            Definitions::pcre('/fail/'),
            Definitions::pcre('/failed/i')
        ];
    }
}

<?php
namespace Test\Interaction\TRegx\CleanRegex\Composite\CompositePattern\anyMatches;

use PHPUnit\Framework\TestCase;
use Test\Utils\Internal;
use TRegx\CleanRegex\Composite\CompositePattern;

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
            Internal::pcre('/https?/i'),
            Internal::pcre('/fail/'),
            Internal::pcre('/failed/i')
        ];
    }
}

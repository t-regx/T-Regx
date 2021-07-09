<?php
namespace Test\Interaction\TRegx\CleanRegex\Composite\CompositePattern\anyMatches;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Composite\CompositePattern;
use TRegx\CleanRegex\Internal\InternalPattern;

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
            InternalPattern::pcre('/https?/i'),
            InternalPattern::pcre('/fail/'),
            InternalPattern::pcre('/failed/i')
        ];
    }
}

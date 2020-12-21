<?php
namespace Test\Interaction\TRegx\CleanRegex\CompositePattern\anyMatches;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\CompositePattern;
use TRegx\CleanRegex\Internal\CompositePatternMapper;
use TRegx\CleanRegex\Pattern;

class CompositePatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldMatch()
    {
        // given
        $pattern = new CompositePattern((new CompositePatternMapper($this->patterns()))->createPatterns());

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
        $pattern = new CompositePattern((new CompositePatternMapper($this->patterns()))->createPatterns());

        // when
        $matches = $pattern->anyMatches('Foo');

        // then
        $this->assertFalse($matches);
    }

    private function patterns(): array
    {
        return [
            Pattern::pcre('/https?/i'),
            Pattern::of('fail'),
            Pattern::pcre('/failed/')
        ];
    }
}

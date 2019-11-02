<?php
namespace Test\Integration\TRegx\CleanRegex\CompositePattern\anyMatches;

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
        $match = $pattern->anyMatches('http');

        // then
        $this->assertTrue($match);
    }

    /**
     * @test
     */
    public function shouldNotMatch()
    {
        // given
        $pattern = new CompositePattern((new CompositePatternMapper($this->patterns()))->createPatterns());

        // when
        $match = $pattern->anyMatches('Foo');

        // then
        $this->assertFalse($match);
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

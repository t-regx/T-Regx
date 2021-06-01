<?php
namespace Test\Interaction\TRegx\CleanRegex\Composite\CompositePattern\allMatch;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Composite\CompositePattern;
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
        $match = $pattern->allMatch('Frodo');

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
        $match = $pattern->allMatch('Frodo2');

        // then
        $this->assertFalse($match);
    }

    private function patterns(): array
    {
        return [
            Pattern::pcre('/^fro/i'),
            Pattern::of('rod'),
            Pattern::of('odo$')
        ];
    }
}

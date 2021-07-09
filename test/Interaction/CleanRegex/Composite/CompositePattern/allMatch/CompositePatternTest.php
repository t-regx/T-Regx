<?php
namespace Test\Interaction\TRegx\CleanRegex\Composite\CompositePattern\allMatch;

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
        $pattern = new CompositePattern($this->patterns());

        // when
        $match = $pattern->allMatch('Frodo2');

        // then
        $this->assertFalse($match);
    }

    private function patterns(): array
    {
        return [
            InternalPattern::pcre('/^fro/i'),
            InternalPattern::pcre('/rod/'),
            InternalPattern::pcre('/odo$/')
        ];
    }
}

<?php
namespace Test\Interaction\TRegx\CleanRegex\Composite\CompositePattern\allMatch;

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
            Internal::pcre('/^fro/i'),
            Internal::pcre('/rod/'),
            Internal::pcre('/odo$/')
        ];
    }
}

<?php
namespace Test\Unit\TRegx\CleanRegex\Composite\CompositePattern\testAny;

use PHPUnit\Framework\TestCase;
use Test\Utils\Definitions;
use TRegx\CleanRegex\Composite\CompositePattern;

/**
 * @covers \TRegx\CleanRegex\Composite\CompositePattern::testAny
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
        $matches = $pattern->testAny('http');

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
        $matches = $pattern->testAny('Foo');

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

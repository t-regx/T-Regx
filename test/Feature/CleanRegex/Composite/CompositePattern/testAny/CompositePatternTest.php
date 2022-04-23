<?php
namespace Test\Feature\TRegx\CleanRegex\Composite\CompositePattern\testAny;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Pattern;
use TRegx\CleanRegex\PcrePattern;

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
        $pattern = Pattern::compose($this->patterns());
        // when, then
        $this->assertTrue($pattern->testAny('http'));
    }

    /**
     * @test
     */
    public function shouldNotMatch()
    {
        // given
        $pattern = Pattern::compose($this->patterns());
        // when, then
        $this->assertFalse($pattern->testAny('Foo'));
    }

    private function patterns(): array
    {
        return [
            PcrePattern::of('/https?/i'),
            PcrePattern::of('/fail/'),
            PcrePattern::of('/failed/i')
        ];
    }
}

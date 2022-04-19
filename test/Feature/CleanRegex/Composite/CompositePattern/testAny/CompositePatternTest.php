<?php
namespace Test\Feature\TRegx\CleanRegex\Composite\CompositePattern\testAny;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Pattern;

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
        // when + then
        $this->assertTrue($pattern->testAny('http'));
    }

    /**
     * @test
     */
    public function shouldNotMatch()
    {
        // given
        $pattern = Pattern::compose($this->patterns());
        // when + then
        $this->assertFalse($pattern->testAny('Foo'));
    }

    private function patterns(): array
    {
        return [
            Pattern::pcre()->of('/https?/i'),
            Pattern::pcre()->of('/fail/'),
            Pattern::pcre()->of('/failed/i')
        ];
    }
}

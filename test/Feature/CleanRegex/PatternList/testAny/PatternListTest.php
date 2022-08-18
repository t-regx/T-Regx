<?php
namespace Test\Feature\CleanRegex\PatternList\testAny;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Pattern;
use TRegx\CleanRegex\PcrePattern;

/**
 * @covers \TRegx\CleanRegex\PatternList::testAny
 */
class PatternListTest extends TestCase
{
    /**
     * @test
     */
    public function shouldMatch()
    {
        // given
        $pattern = Pattern::list($this->patterns());
        // when, then
        $this->assertTrue($pattern->testAny('http'));
    }

    /**
     * @test
     */
    public function shouldNotMatch()
    {
        // given
        $pattern = Pattern::list($this->patterns());
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

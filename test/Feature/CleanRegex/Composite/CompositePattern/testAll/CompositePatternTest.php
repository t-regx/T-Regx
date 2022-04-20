<?php
namespace Test\Feature\TRegx\CleanRegex\Composite\CompositePattern\testAll;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Pattern;
use TRegx\CleanRegex\PcrePattern;

/**
 * @covers \TRegx\CleanRegex\Composite\CompositePattern::testAll
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
        $this->assertTrue($pattern->testAll('Frodo'));
    }

    /**
     * @test
     */
    public function shouldNotMatch()
    {
        // given
        $pattern = Pattern::compose($this->patterns());
        // when + then
        $this->assertFalse($pattern->testAll('Frodo2'));
    }

    private function patterns(): array
    {
        return [
            PcrePattern::of('/^fro/i'),
            PcrePattern::of('/rod/'),
            PcrePattern::of('/odo$/')
        ];
    }
}

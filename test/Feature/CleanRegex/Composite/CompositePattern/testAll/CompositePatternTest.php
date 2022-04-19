<?php
namespace Test\Feature\TRegx\CleanRegex\Composite\CompositePattern\testAll;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Pattern;

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
            Pattern::pcre()->of('/^fro/i'),
            Pattern::pcre()->of('/rod/'),
            Pattern::pcre()->of('/odo$/')
        ];
    }
}

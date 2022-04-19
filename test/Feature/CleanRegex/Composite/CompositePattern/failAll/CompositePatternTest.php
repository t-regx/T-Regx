<?php
namespace Test\Feature\TRegx\CleanRegex\Composite\CompositePattern\failAll;

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
    public function testAllPassingPatterns()
    {
        // given
        $pattern = Pattern::compose(['^P', 'R', 'E$']);
        // when + then
        $this->assertFalse($pattern->failAll('PRE'));
    }

    /**
     * @test
     */
    public function testOneFailingPattern()
    {
        // given
        $pattern = Pattern::compose(['^P$', 'R', 'E', 'x']);
        // when + then
        $this->assertFalse($pattern->failAll('PRE'));
    }

    /**
     * @test
     */
    public function testAllFailingPatterns()
    {
        // given
        $pattern = Pattern::compose(['1', '2', '3', '4']);
        // when + then
        $this->assertTrue($pattern->failAll('PRE'));
    }
}

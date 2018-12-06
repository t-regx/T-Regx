<?php
namespace Test\Integration\TRegx\CleanRegex\CompositePattern\allMatch;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\CompositePattern;
use TRegx\CleanRegex\Internal\CompositePatternMapper;

class CompositePatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldMatch()
    {
        // given
        $pattern = new CompositePattern((new CompositePatternMapper(['/^fro/i', 'rod', 'odo$']))->createPatterns());

        // when
        $match = $pattern->allMatch('Frodo');

        // then
        $this->assertTrue($match);
    }

    public function shouldNotMatch()
    {
        // given
        $pattern = new CompositePattern((new CompositePatternMapper(['/^fro/i', 'rod', 'odo$']))->createPatterns());

        // when
        $match = $pattern->allMatch('Frodo2');

        // then
        $this->assertFalse($match);
    }
}

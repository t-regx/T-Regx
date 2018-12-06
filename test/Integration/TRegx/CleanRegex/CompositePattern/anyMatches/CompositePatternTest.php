<?php
namespace Test\Integration\TRegx\CleanRegex\CompositePattern\anyMatches;

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
        $pattern = new CompositePattern((new CompositePatternMapper(['/https?/i', 'fail', '/failed/']))->createPatterns());

        // when
        $match = $pattern->anyMatches('http');

        // then
        $this->assertTrue($match);
    }

    /**
     * @test
     */
    public function shouldNotMatch()
    {
        // given
        $pattern = new CompositePattern((new CompositePatternMapper(['/https?$/i', 'fail', '/failed/']))->createPatterns());

        // when
        $match = $pattern->anyMatches('httpz');

        // then
        $this->assertFalse($match);
    }
}

<?php
namespace Danon\CleanRegex;

use PHPUnit\Framework\TestCase;

class PatternQuoteTest extends TestCase
{
    /**
     * @test
     * @dataProvider \Test\Danon\DataProviders::invalidPregPatterns()
     * @param string $invalidPattern
     */
    public function shouldQuoteWithoutException(string $invalidPattern)
    {
        // when
        (new Pattern($invalidPattern))->quote();

        // then
        $this->assertTrue(true);
    }
}

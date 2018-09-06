<?php
namespace Test\Unit\CleanRegex;

use CleanRegex\Pattern;
use PHPUnit\Framework\TestCase;

class PatternQuoteTest extends TestCase
{
    /**
     * @test
     * @dataProvider \Test\DataProviders::invalidPregPatterns()
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

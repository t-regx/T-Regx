<?php
namespace Test\Unit\TRegx\CleanRegex;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Pattern;

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

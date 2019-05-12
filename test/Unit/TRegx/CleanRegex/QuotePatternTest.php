<?php
namespace Test\Unit\TRegx\CleanRegex;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\QuotePattern;

class QuotePatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldQuote()
    {
        // given
        $quotePattern = new QuotePattern('Did you?');

        // when
        $quoted = $quotePattern->quote();

        // then
        $this->assertEquals('Did you\\?', $quoted);
    }

    /**
     * @test
     * @dataProvider \Test\DataProviders::invalidPregPatterns()
     * @param string $invalidPattern
     */
    public function shouldQuoteWithoutException(string $invalidPattern)
    {
        // given
        $quotePattern = new QuotePattern($invalidPattern);

        // when
        $quotePattern->quote();

        // then
        $this->assertTrue(true);
    }
}

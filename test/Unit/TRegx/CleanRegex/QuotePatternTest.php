<?php
namespace Test\Unit\TRegx\CleanRegex;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\InternalPattern;
use TRegx\CleanRegex\QuotePattern;

class QuotePatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldQuote()
    {
        // given
        $pattern = new InternalPattern('Did you?');
        $quotePattern = new QuotePattern($pattern);

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
        $pattern = new InternalPattern($invalidPattern);
        $quotePattern = new QuotePattern($pattern);

        // when
        $quotePattern->quote();

        // then
        $this->assertTrue(true);
    }
}

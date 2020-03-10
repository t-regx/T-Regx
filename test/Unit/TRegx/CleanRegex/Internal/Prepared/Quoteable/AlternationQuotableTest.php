<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Prepared\Quoteable;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\Prepared\Quoteable\AlternationQuotable;

class AlternationQuotableTest extends TestCase
{
    /**
     * @test
     */
    public function shouldQuote()
    {
        // given
        $quotable = new AlternationQuotable(['/()', '^#$']);

        // when
        $result = $quotable->quote('');

        // then
        $this->assertEquals('/\(\)|\^\#\$', $result);
    }

    /**
     * @test
     */
    public function shouldQuoteDelimiter()
    {
        // given
        $quotable = new AlternationQuotable(['a', '%b']);

        // when
        $result = $quotable->quote('%');

        // then
        $this->assertEquals('a|\%b', $result);
    }

    /**
     * @test
     */
    public function shouldRemoveDuplicates()
    {
        // given
        $quotable = new AlternationQuotable(['a', 'b', 'a', 'c']);

        // when
        $result = $quotable->quote('');

        // then
        $this->assertEquals('a|b|c', $result);
    }
}

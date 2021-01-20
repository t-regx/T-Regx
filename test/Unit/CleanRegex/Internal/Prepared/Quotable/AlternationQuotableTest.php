<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Prepared\Quotable;

use PHPUnit\Framework\TestCase;
use Test\Utils\Functions;
use TRegx\CleanRegex\Internal\Prepared\Quotable\AlternationQuotable;

class AlternationQuotableTest extends TestCase
{
    /**
     * @test
     */
    public function shouldQuote()
    {
        // given
        $quotable = new AlternationQuotable(['/()', '^#$'], null);

        // when
        $result = $quotable->quote('~');

        // then
        $this->assertSame('(?:/\(\)|\^\#\$)', $result);
    }

    /**
     * @test
     */
    public function shouldQuoteDelimiter()
    {
        // given
        $quotable = new AlternationQuotable(['a', '%b'], null);

        // when
        $result = $quotable->quote('%');

        // then
        $this->assertSame('(?:a|\%b)', $result);
    }

    /**
     * @test
     */
    public function shouldRemoveDuplicates_caseSensitive()
    {
        // given
        $quotable = new AlternationQuotable(['a', 'FOO', 'a', 'c', 'foo'], Functions::identity());

        // when
        $result = $quotable->quote('/');

        // then
        $this->assertSame('(?:a|FOO|c|foo)', $result);
    }

    /**
     * @test
     */
    public function shouldRemoveDuplicates_caseInsensitive()
    {
        // given
        $quotable = new AlternationQuotable(['a', 'FOO', 'a', 'a', 'c', 'foo'], 'strtolower');

        // when
        $result = $quotable->quote('/');

        // then
        $this->assertSame('(?:a|FOO|c)', $result);
    }

    /**
     * @test
     */
    public function shouldAddAnEmptyProduct_toIndicateAnEmptyString()
    {
        // given
        $quotable = new AlternationQuotable(['a', '', '', 'b'], null);

        // when
        $result = $quotable->quote('/');

        // then
        $this->assertSame('(?:a|b|)', $result);
    }

    /**
     * @test
     */
    public function shouldIgnoreOtherCharacters()
    {
        // given
        $quotable = new AlternationQuotable(['|', ' ', '0'], null);

        // when
        $result = $quotable->quote('/');

        // then
        $this->assertSame('(?:\||\ |0)', $result);
    }
}

<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Prepared\Quotable;

use InvalidArgumentException;
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
        $quotable = new AlternationQuotable(['a', 'FOO', 'a', 'a', 'c', 'foo'], 'strToLower');

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

    /**
     * @test
     */
    public function shouldThrowForArrayValues()
    {
        // given
        $quotable = new AlternationQuotable(['|', []], null);

        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid bound alternate value. Expected string, but array (0) given');

        // when
        $quotable->quote('/');
    }

    /**
     * @test
     */
    public function shouldThrowForIntegerValues()
    {
        // given
        $quotable = new AlternationQuotable(['|', 5], null);

        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid bound alternate value. Expected string, but integer (5) given');

        // when
        $quotable->quote('/');
    }
}

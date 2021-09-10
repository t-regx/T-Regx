<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Prepared\Word;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\Prepared\Word\AlternationWord;

/**
 * @covers \TRegx\CleanRegex\Internal\Prepared\Word\AlternationWord
 */
class AlternationWordTest extends TestCase
{
    /**
     * @test
     */
    public function shouldQuote()
    {
        // given
        $word = new AlternationWord(['/()', '^#$']);

        // when
        $result = $word->quoted('~');

        // then
        $this->assertSame('(?:/\(\)|\^\#\$)', $result);
    }

    /**
     * @test
     */
    public function shouldQuoteDelimiter()
    {
        // given
        $word = new AlternationWord(['a', '%b']);

        // when
        $result = $word->quoted('%');

        // then
        $this->assertSame('(?:a|\%b)', $result);
    }

    /**
     * @test
     */
    public function shouldAddAnEmptyProduct_toIndicateAnEmptyString()
    {
        // given
        $word = new AlternationWord(['a', '', '', 'b']);

        // when
        $result = $word->quoted('/');

        // then
        $this->assertSame('(?:a|b|)', $result);
    }

    /**
     * @test
     */
    public function shouldNotRemoveFalsyStrings()
    {
        // given
        $word = new AlternationWord(['|', ' ', '0']);

        // when
        $result = $word->quoted('/');

        // then
        $this->assertSame('(?:\||\ |0)', $result);
    }

    /**
     * @test
     */
    public function shouldThrowForArrayValues()
    {
        // given
        $word = new AlternationWord(['|', []]);

        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid bound alternate value. Expected string, but array (0) given');

        // when
        $word->quoted('/');
    }

    /**
     * @test
     */
    public function shouldThrowForIntegerValues()
    {
        // given
        $word = new AlternationWord(['|', 5]);

        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid bound alternate value. Expected string, but integer (5) given');

        // when
        $word->quoted('/');
    }

    /**
     * @test
     */
    public function shouldThrowForFalse()
    {
        // given
        $word = new AlternationWord([false]);

        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid bound alternate value. Expected string, but boolean (false) given');

        // when
        $word->quoted('/');
    }

    /**
     * @test
     */
    public function shouldGetWithFalsyString()
    {
        // given
        $word = new AlternationWord(['0']);

        // when
        $quote = $word->quoted('/');

        // then
        $this->assertSame('(?:0)', $quote);
    }
}

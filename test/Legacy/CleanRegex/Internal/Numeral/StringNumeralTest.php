<?php
namespace Test\Legacy\CleanRegex\Internal\Numeral;

use PHPUnit\Framework\TestCase;
use Test\Fakes\CleanRegex\Internal\Numeral\ThrowBase;
use Test\Utils\Integers;
use TRegx\CleanRegex\Internal\Numeral\Base;
use TRegx\CleanRegex\Internal\Numeral\NumeralFormatException;
use TRegx\CleanRegex\Internal\Numeral\NumeralOverflowException;
use TRegx\CleanRegex\Internal\Numeral\StringNumeral;
use TRegx\DataProvider\CrossDataProviders;

/**
 * @deprecated
 * @covers \TRegx\CleanRegex\Internal\Numeral\StringNumeral
 */
class StringNumeralTest extends TestCase
{
    use Integers;

    /**
     * @test
     * @dataProvider integerBoundryValues
     */
    public function shouldParse(string $input, $expected, int $base)
    {
        // given
        $numeral = new StringNumeral($input);
        // when
        $integer = $numeral->asInt(new Base($base));
        // then
        $this->assertSame($expected, $integer);
    }

    /**
     * @test
     * @dataProvider integerOverflownValues
     */
    public function shouldThrowForOverflow(string $value, int $base)
    {
        // given
        $number = new StringNumeral($value);
        // then
        $this->expectException(NumeralOverflowException::class);
        // when
        $number->asInt(new Base($base));
    }

    /**
     * @test
     * @dataProvider malformedValues
     */
    public function shouldThrowForMalformedValues(int $base, string $value)
    {
        // given
        $number = new StringNumeral($value);
        // then
        $this->expectException(NumeralFormatException::class);
        // when
        $number->asInt(new Base($base));
    }

    /**
     * @test
     * @dataProvider cornerDigits
     */
    public function shouldThrowForCornerDigit(int $base, string $value)
    {
        // given
        $number = new StringNumeral($value);
        // then
        $this->expectException(NumeralFormatException::class);
        // when
        $number->asInt(new Base($base));
    }

    /**
     * @test
     */
    public function testZero()
    {
        // given
        $number = new StringNumeral('000');
        // when
        $format = $number->asInt(new Base(12));
        // then
        $this->assertSame(0, $format);
    }

    /**
     * @test
     */
    public function shouldThrowMalformedForEmpty()
    {
        // given
        $number = new StringNumeral('');
        // then
        $this->expectException(NumeralFormatException::class);
        // when
        $number->asInt(new ThrowBase());
    }

    /**
     * @test
     */
    public function shouldParseCaseInsensitively()
    {
        // given
        $number = new StringNumeral('-ABC');
        // when
        $integer = $number->asInt(new Base(13));
        // then
        $this->assertSame(-1845, $integer);
    }

    /**
     * @test
     * @dataProvider caseInsensitiveBounds
     */
    public function shouldBeCaseInsensitiveForBounds(string $number, int $expected)
    {
        // given
        $number = new StringNumeral($number);
        // when
        $integer = $number->asInt(new Base(36));
        // then
        $this->assertSame($expected, $integer);
    }

    public function caseInsensitiveBounds(): array
    {
        return \array_merge(
            $this->onArchitecture32([['-ZIK0ZK', -2147483647 - 1]]),
            $this->onArchitecture64([['-1Y2P0IJ32E8E8', -9223372036854775807 - 1]])
        );
    }

    public function cornerDigits(): array
    {
        return [[9, '9'], [2, '2'], [35, 'z'], [9, '-9'], [2, '-2'], [35, '-z']];
    }

    public function malformedValues(): array
    {
        return CrossDataProviders::cross([[2], [10], [16], [36]], [['--1'], ['1-1'], ['+2'], ['-'], ['\n1']]);
    }
}

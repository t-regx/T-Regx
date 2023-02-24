<?php
namespace Test\Feature\CleanRegex\match\_integer\toInt;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Test\Utils\Integers;
use TRegx\CleanRegex\Exception\IntegerFormatException;
use TRegx\CleanRegex\Exception\IntegerOverflowException;
use TRegx\CleanRegex\Match\Detail;
use TRegx\CleanRegex\Pattern;

class DetailTest extends TestCase
{
    use Integers;

    /**
     * @test
     * @dataProvider integersBase10
     * @param string $string
     * @param int $expected
     */
    public function shouldParseIntegerBase10(string $string, int $expected)
    {
        // given
        $detail = Pattern::of('-?\d+')->match($string)->first();
        // when, then
        $this->assertSame($expected, $detail->toInt());
    }

    /**
     * @test
     * @dataProvider integers
     */
    public function shouldBeIntegerGivenBase(string $input, int $expected, int $base)
    {
        // given
        $detail = $this->detail($input);
        // when, then
        $this->assertSame($expected, $detail->toInt($base));
    }

    /**
     * @test
     */
    public function shouldParseIntegerBase4()
    {
        // given
        $detail = $this->detail('-321');
        // when, then
        $this->assertSame(-57, $detail->toInt(4));
    }

    /**
     * @test
     */
    public function shouldThrowForMalformedInteger()
    {
        // given
        $detail = $this->detail('Foo');
        // then
        $this->expectException(IntegerFormatException::class);
        $this->expectExceptionMessage("Expected to parse 'Foo', but it is not a valid integer in base 10");
        // when
        $detail->toInt();
    }

    /**
     * @test
     */
    public function shouldThrowForIntegerEmpty()
    {
        // given
        $detail = $this->detail('');
        // then
        $this->expectException(IntegerFormatException::class);
        $this->expectExceptionMessage("Expected to parse '', but it is not a valid integer in base 2");
        // when
        $detail->toInt(2);
    }

    /**
     * @test
     */
    public function shouldThrowForIntegerOverflownBase16()
    {
        // given
        $detail = Pattern::of('-\d+')->match('-9223372036854770000')->first();
        // then
        $this->expectException(IntegerOverflowException::class);
        $this->expectExceptionMessage("Expected to parse '-9223372036854770000', but it exceeds integer size on this architecture in base 16");
        // when
        $detail->toInt(16);
    }

    /**
     * @test
     * @dataProvider invalidBases
     */
    public function shouldThrowForInvalidBase(int $base)
    {
        // given
        $detail = $this->detail('Foo');
        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Invalid base: $base (supported bases 2-36, case-insensitive)");
        // when
        $detail->toInt($base);
    }

    /**
     * @test
     * @dataProvider integerBoundryValues
     */
    public function shouldParseBoundryValues(string $input, int $expected, int $base)
    {
        // given
        $detail = $this->detail($input);
        // when, then
        $this->assertSame($expected, $detail->toInt($base));
    }

    /**
     * @test
     * @dataProvider integerOverflownValues
     */
    public function shouldThrowForIntegerOverflow(string $value, int $base)
    {
        // given
        $detail = $this->detail($value);
        // then
        $this->expectException(IntegerOverflowException::class);
        $this->expectExceptionMessage("Expected to parse '$value', but it exceeds integer size on this architecture in base $base");
        // when
        $detail->toInt($base);
    }

    /**
     * @test
     * @dataProvider integersMalformed
     */
    public function shouldThrowForIntegerMalformed(int $base, string $text)
    {
        // given
        $detail = $this->detail($text);
        // then
        $this->expectException(IntegerFormatException::class);
        $this->expectExceptionMessage("Expected to parse '$text', but it is not a valid integer in base $base");
        // when
        $detail->toInt($base);
    }

    /**
     * @test
     * @dataProvider cornerDigits
     */
    public function shouldParseIntegerCornerDigit(string $cornerDigit, int $value, int $base)
    {
        // given
        $detail = $this->detail($cornerDigit);
        // when, then
        $this->assertEquals($value, $detail->toInt($base));
    }

    /**
     * @test
     * @dataProvider cornerDigits
     */
    public function shouldThrowForAboveCornerDigit(string $cornerDigit, int $value, int $base)
    {
        // given
        $detail = $this->detail($cornerDigit);
        // then
        $this->expectException(IntegerFormatException::class);
        $this->expectExceptionMessage("Expected to parse '$cornerDigit', but it is not a valid integer in base " . ($base - 1));
        // when
        $detail->toInt($base - 1);
    }

    /**
     * @test
     */
    public function shouldParseCaseInsensitively()
    {
        // given
        $detail = $this->detail('-ABC');
        // when, then
        $this->assertSame(-1845, $detail->toInt(13));
    }

    /**
     * @test
     */
    public function shouldThrowForPseudoInteger()
    {
        // given
        $detail = Pattern::of('.*', 's')->match('1e3')->first();
        // then
        $this->expectException(IntegerFormatException::class);
        $this->expectExceptionMessage("Expected to parse '1e3', but it is not a valid integer in base 10");
        // when
        $detail->toInt();
    }

    /**
     * @test
     * @dataProvider integerBoundryValuesCaseInsensitive
     */
    public function shouldBeCaseInsensitiveForBounds(string $number, int $expected)
    {
        // given
        $detail = $this->detail($number);
        // when, then
        $this->assertSame($expected, $detail->toInt(36));
    }

    private function detail(string $text): Detail
    {
        return Pattern::literal($text)->match($text)->first();
    }
}

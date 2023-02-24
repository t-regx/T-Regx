<?php
namespace Test\Feature\CleanRegex\match\_integer\isInt;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Test\Utils\Integers;
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
    public function shouldBeIntegerBase10(string $string, int $expected)
    {
        // given
        $detail = Pattern::of('-?\d+')->match($string)->first();
        // when, then
        $this->assertTrue($detail->isInt());
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
        $this->assertTrue($detail->isInt($base));
    }

    /**
     * @test
     */
    public function shouldBeIntegerBase4()
    {
        // given
        $detail = $this->detail('-321');
        // when, then
        $this->assertTrue($detail->isInt(4));
    }

    /**
     * @test
     */
    public function shouldNotBeInteger()
    {
        // given
        $detail = $this->detail('Foo');
        // when, then
        $this->assertFalse($detail->isInt());
    }

    /**
     * @test
     */
    public function shouldNotBeIntegerEmpty()
    {
        // given
        $detail = $this->detail('');
        // when, then
        $this->assertFalse($detail->isInt(2));
    }

    /**
     * @test
     */
    public function shouldNotBeIntegerIntegerOverflownBase16()
    {
        // given
        $detail = Pattern::of('-\d+')->match('-9223372036854770000')->first();
        // when, then
        $this->assertFalse($detail->isInt(16));
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
        $detail->isInt($base);
    }

    /**
     * @test
     * @dataProvider integerBoundryValues
     */
    public function shouldBeIntegerBoundryValues(string $input, int $expected, int $base)
    {
        // given
        $detail = $this->detail($input);
        // when, then
        $this->assertTrue($detail->isInt($base));
    }

    /**
     * @test
     * @dataProvider integerOverflownValues
     */
    public function shouldNotBeIntegerOverflow(string $value, int $base)
    {
        // given
        $detail = $this->detail($value);
        // when, then
        $this->assertFalse($detail->isInt($base));
    }

    /**
     * @test
     * @dataProvider integersMalformed
     */
    public function shouldNotBeIntegerMalformed(int $base, string $text)
    {
        // given
        $detail = $this->detail($text);
        // when, then
        $this->assertFalse($detail->isInt($base));
    }

    /**
     * @test
     * @dataProvider cornerDigits
     */
    public function shouldBeIntegerCornerDigit(string $cornerDigit, int $value, int $base)
    {
        // given
        $detail = $this->detail($cornerDigit);
        // when, then
        $this->assertTrue($detail->isInt($base));
    }

    /**
     * @test
     * @dataProvider cornerDigits
     */
    public function shouldNotBeIntegerAboveCornerDigit(string $cornerDigit, int $value, int $base)
    {
        // given
        $detail = $this->detail($cornerDigit);
        // when, then
        $this->assertFalse($detail->isInt($base - 1));
    }

    /**
     * @test
     */
    public function shouldBeIntegerCaseInsensitively()
    {
        // given
        $detail = $this->detail('-ABC');
        // when, then
        $this->assertTrue($detail->isInt(13));
    }

    /**
     * @test
     */
    public function shouldNotBeIntegerPseudoInteger()
    {
        // given
        $detail = Pattern::of('.*', 's')->match('1e3')->first();
        // when, then
        $this->assertFalse($detail->isInt());
    }

    /**
     * @test
     * @dataProvider integerBoundryValuesCaseInsensitive
     */
    public function shouldBeIntegerBoundryValueCaseInsensitive(string $number)
    {
        // given
        $detail = $this->detail($number);
        // when, then
        $this->assertTrue($detail->isInt(36));
    }

    private function detail(string $text): Detail
    {
        return Pattern::literal($text)->match($text)->first();
    }
}

<?php
namespace Test\Feature\CleanRegex\match\Detail\toInt;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Test\Utils\TestCase\TestCaseExactMessage;
use TRegx\CleanRegex\Exception\IntegerFormatException;
use TRegx\CleanRegex\Exception\IntegerOverflowException;
use TRegx\CleanRegex\Pattern;

class DetailTest extends TestCase
{
    use TestCaseExactMessage;

    /**
     * @test
     * @dataProvider validIntegers
     * @param string $string
     * @param int $expected
     */
    public function shouldParseInt(string $string, int $expected)
    {
        // given
        $detail = Pattern::of('-?\d+')->match($string)->first();
        // when
        $result = $detail->toInt();
        // then
        $this->assertSame($expected, $result);
    }

    public function validIntegers(): array
    {
        return [
            ['1', 1],
            ['-1', -1],
            ['0', 0],
            ['000', 0],
            ['011', 11],
            ['0001', 1],
        ];
    }

    /**
     * @test
     */
    public function shouldIntBase4()
    {
        // given
        $detail = Pattern::of('-\d+')->match('-123')->first();
        // when
        $result = $detail->toInt(4);
        // then
        $this->assertSame(-27, $result);
    }

    /**
     * @test
     */
    public function shouldThrowForInvalidBase()
    {
        // given
        $detail = Pattern::of('Foo')->match('Foo')->first();
        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid base: -1 (supported bases 2-36, case-insensitive)');
        // when
        $detail->toInt(-1);
    }

    /**
     * @test
     */
    public function shouldThrow_forPseudoInteger_becausePhpSucks()
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
     */
    public function shouldThrow_forInvalidInteger_base4()
    {
        // given
        $detail = Pattern::of('(4)')->match('4')->first();
        // then
        $this->expectException(IntegerFormatException::class);
        $this->expectExceptionMessage("Expected to parse '4', but it is not a valid integer in base 4");
        // when
        $detail->toInt(4);
    }

    /**
     * @test
     */
    public function shouldThrow_forMalformedInteger()
    {
        // given
        $detail = Pattern::of('Foo')->match('Foo')->first();
        // then
        $this->expectException(IntegerFormatException::class);
        $this->expectExceptionMessage("Expected to parse 'Foo', but it is not a valid integer in base 10");
        // when
        $detail->toInt();
    }

    /**
     * @test
     */
    public function shouldThrow_forOverflownInteger()
    {
        // given
        $detail = Pattern::of('-\d+')->match('-9223372036854775809')->first();
        // then
        $this->expectException(IntegerOverflowException::class);
        $this->expectExceptionMessage("Expected to parse '-9223372036854775809', but it exceeds integer size on this architecture in base 10");
        // when
        $detail->toInt();
    }

    /**
     * @test
     */
    public function shouldParseIntBase4()
    {
        // given
        $detail = Pattern::of('-?\d+')->match('-321')->first();
        // when
        $result = $detail->toInt(4);
        // then
        $this->assertSame(-57, $result);
    }

    /**
     * @test
     */
    public function shouldThrow_forOverflownIntegerBase16()
    {
        // given
        $detail = Pattern::of('-\d+')->match('-9223372036854770000')->first();
        // then
        $this->expectException(IntegerOverflowException::class);
        $this->expectExceptionMessage("Expected to parse '-9223372036854770000', but it exceeds integer size on this architecture in base 16");
        // when
        $detail->toInt(16);
    }
}

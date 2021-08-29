<?php
namespace Test\Feature\TRegx\CleanRegex\Match\Details\toInt;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Test\Utils\ExactExceptionMessage;
use TRegx\CleanRegex\Exception\IntegerFormatException;
use TRegx\CleanRegex\Exception\IntegerOverflowException;
use TRegx\CleanRegex\Match\Details\Detail;
use function pattern;

/**
 * @coversNothing
 */
class MatchDetailTest extends TestCase
{
    use ExactExceptionMessage;

    /**
     * @test
     * @dataProvider validIntegers
     * @param string $string
     * @param int $expected
     */
    public function shouldParseInt(string $string, int $expected)
    {
        // given
        $result = pattern('-?\d+')
            ->match($string)
            ->first(function (Detail $detail) {
                // when
                return $detail->toInt();
            });

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
        $result = pattern('-\d+')->match('-123')->first(function (Detail $detail) {
            // when
            return $detail->toInt(4);
        });

        // then
        $this->assertSame(-27, $result);
    }

    /**
     * @test
     */
    public function shouldThrowForInvalidBase()
    {
        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid base: -1 (supported bases 2-36, case-insensitive)');

        // given
        pattern('Foo')->match('Foo')->first(function (Detail $detail) {
            return $detail->toInt(-1);
        });
    }

    /**
     * @test
     */
    public function shouldThrow_forPseudoInteger_becausePhpSucks()
    {
        // then
        $this->expectException(IntegerFormatException::class);
        $this->expectExceptionMessage("Expected to parse '1e3', but it is not a valid integer in base 10");

        // given
        pattern('.*', 's')
            ->match('1e3')
            ->first(function (Detail $detail) {
                // when
                return $detail->toInt();
            });
    }

    /**
     * @test
     */
    public function shouldThrow_forInvalidInteger_base4()
    {
        // then
        $this->expectException(IntegerFormatException::class);
        $this->expectExceptionMessage("Expected to parse '4', but it is not a valid integer in base 4");

        // given
        pattern('(4)')
            ->match('4')
            ->first(function (Detail $detail) {
                // when
                return $detail->toInt(4);
            });
    }

    /**
     * @test
     */
    public function shouldThrow_forMalformedInteger()
    {
        // then
        $this->expectException(IntegerFormatException::class);
        $this->expectExceptionMessage("Expected to parse 'Foo', but it is not a valid integer in base 10");

        // given
        pattern('Foo')->match('Foo')->first(function (Detail $detail) {
            // when
            return $detail->toInt();
        });
    }

    /**
     * @test
     */
    public function shouldThrow_forOverflownInteger()
    {
        // then
        $this->expectException(IntegerOverflowException::class);
        $this->expectExceptionMessage("Expected to parse '-9223372036854775809', but it exceeds integer size on this architecture in base 10");

        // given
        pattern('-\d+')->match('-9223372036854775809')
            ->first(function (Detail $detail) {
                // when
                return $detail->toInt();
            });
    }

    /**
     * @test
     */
    public function shouldParseIntBase4()
    {
        // given
        $result = pattern('-?\d+')->match('-321')->first(function (Detail $detail) {
            // when
            return $detail->toInt(4);
        });

        // then
        $this->assertSame(-57, $result);
    }

    /**
     * @test
     */
    public function shouldThrow_forOverflownIntegerBase16()
    {
        // then
        $this->expectException(IntegerOverflowException::class);
        $this->expectExceptionMessage("Expected to parse '-9223372036854770000', but it exceeds integer size on this architecture in base 16");

        // given
        pattern('-\d+')->match('-9223372036854770000')
            ->first(function (Detail $detail) {
                // when
                return $detail->toInt(16);
            });
    }
}

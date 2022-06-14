<?php
namespace Test\Unit\CleanRegex\Internal\Numeral;

use PHPUnit\Framework\TestCase;
use Test\Utils\Agnostic\ArchitectureDependant;
use TRegx\CleanRegex\Internal\Numeral\Base;
use TRegx\CleanRegex\Internal\Numeral\NumeralFormatException;
use TRegx\CleanRegex\Internal\Numeral\NumeralOverflowException;
use TRegx\CleanRegex\Internal\Numeral\PositiveNotation;
use TRegx\DataProvider\CrossDataProviders;

/**
 * @covers \TRegx\CleanRegex\Internal\Numeral\PositiveNotation
 */
class PositiveNotationTest extends TestCase
{
    use ArchitectureDependant;

    /**
     * @test
     * @dataProvider dataProvider
     */
    public function shouldParse(string $input, int $expected, int $base)
    {
        // given
        $number = new PositiveNotation($input);

        // when
        $integer = $number->integer(new Base($base));

        // then
        $this->assertSame($expected, $integer);
    }

    public function dataProvider(): array
    {
        return \array_merge(
            $this->zeros(),
            $this->standardValues(),
            $this->maximumOnArchitecture32(),
            $this->onArchitecture64($this->allDigits()),
            $this->onArchitecture64($this->maximumOnArchitecture64()),
            $this->onArchitecture64([['1234567890abcdef', 1311768467294899695, 16]])
        );
    }

    private function zeros(): array
    {
        return [
            ['00', 0, 2],
            ['00', 0, 3],
            ['00', 0, 8],
            ['00', 0, 9],
            ['00', 0, 10],
            ['00', 0, 16],
            ['00', 0, 36],
        ];
    }

    private function standardValues(): array
    {
        return [
            ['1100', 12, 2],
            ['13', 13, 10],
            ['g', 16, 17],
            ['5h4b2f', 11259375, 18],
            ['0005h4b2f', 11259375, 18],
            ['70abcdef', 1890307567, 16],
            ['70ABCDEF', 1890307567, 16],
        ];
    }

    private function maximumOnArchitecture32(): array
    {
        return [
            ['1111111111111111111111111111111', 2147483647, 2],
            ['17777777777', 2147483647, 8],
            ['2147483647', 2147483647, 10],
            ['7fffffff', 2147483647, 16],
            ['zik0zj', 2147483647, 36],
        ];
    }

    private function maximumOnArchitecture64(): array
    {
        return [
            ['111111111111111111111111111111111111111111111111111111111111111', 9223372036854775807, 2],
            ['2021110011022210012102010021220101220221', 9223372036854775807, 3],
            ['777777777777777777777', 9223372036854775807, 8],
            ['9223372036854775807', 9223372036854775807, 10],
            ['7fffffffffffffff', 9223372036854775807, 16],
            ['1y2p0ij32e8e6', 9223372036854775806, 36],
            ['1y2p0ij32e8e7', 9223372036854775807, 36]
        ];
    }

    private function allDigits(): array
    {
        return [
            ['1023456789ab', 131833611800834051, 36],
            ['cdefghijklm', 45234887606199562, 36],
            ['opqrstuvwxyz', 3253043841247738619, 36]
        ];
    }

    /**
     * @test
     * @dataProvider overflown
     */
    public function shouldThrowForOverflow(string $value, int $base)
    {
        // given
        $number = new PositiveNotation($value);

        // then
        $this->expectException(NumeralOverflowException::class);

        // when
        $number->integer(new Base($base));
    }

    public function overflown(): array
    {
        return \array_merge(
            $this->onArchitecture32($this->overflownOnArchitecture32()),
            $this->overflownOnArchitecture64()
        );
    }

    public function overflownOnArchitecture32(): array
    {
        return [
            ['10000000000000000000000000000000', 2],
            ['1000000000000000000000', 8],
            ['2147483648', 10],
            ['80000000', 16],
            ['zik0zk', 36],
        ];
    }

    private function overflownOnArchitecture64(): array
    {
        return [
            ['1000000000000000000000000000000000000000000000000000000000000000', 2],
            ['1000000000000000000000', 8],
            ['9223372036854775808', 10],
            ['8000000000000000', 16],
            ['1y2p0ij32e8e8', 36],
        ];
    }

    /**
     * @test
     * @dataProvider malformedValues
     */
    public function shouldThrowForMalformedValues(string $value, int $base)
    {
        // given
        $number = new PositiveNotation($value);

        // then
        $this->expectException(NumeralFormatException::class);

        // when
        $number->integer(new Base($base));
    }

    public function malformedValues(): array
    {
        return CrossDataProviders::cross([
            [''], ['.'], [','], [' '], ['-1'], ['-0'], ['--1'], ['1-1'], ['+2'], ['-'], ["\n1"]],
            [[2], [10], [16], [36]]
        );
    }

    /**
     * @test
     * @dataProvider cornerDigits
     */
    public function shouldThrowForUnacceptedCharacters(string $value, int $_, int $base)
    {
        // given
        $number = new PositiveNotation($value);

        // then
        $this->expectException(NumeralFormatException::class);

        // when
        $number->integer(new Base($base - 1));
    }

    /**
     * @test
     * @dataProvider cornerDigits
     */
    public function shouldBaseAcceptTheCornerDigit(string $value, int $expected, int $base)
    {
        // given
        $number = new PositiveNotation($value);

        // when
        $integer = $number->integer(new Base($base));

        // then
        $this->assertSame($expected, $integer);
    }

    public function cornerDigits(): array
    {
        return [
            ['2', 2, 3],
            ['3', 3, 4],
            ['4', 4, 5],
            ['5', 5, 6],
            ['6', 6, 7],
            ['7', 7, 8],
            ['8', 8, 9],
            ['9', 9, 10],
            ['a', 10, 11],
            ['b', 11, 12],
            ['c', 12, 13],
            ['d', 13, 14],
            ['e', 14, 15],
            ['f', 15, 16],
            ['g', 16, 17],
            ['h', 17, 18],
            ['i', 18, 19],
            ['j', 19, 20],
            ['k', 20, 21],
            ['l', 21, 22],
            ['m', 22, 23],
            ['n', 23, 24],
            ['o', 24, 25],
            ['p', 25, 26],
            ['q', 26, 27],
            ['r', 27, 28],
            ['s', 28, 29],
            ['t', 29, 30],
            ['u', 30, 31],
            ['v', 31, 32],
            ['w', 32, 33],
            ['x', 33, 34],
            ['y', 34, 35],
            ['z', 35, 36],
        ];
    }

    /**
     * @test
     */
    public function shouldNotTrimRightZeros()
    {
        // given
        $number = new PositiveNotation('10');

        // when
        $integer = $number->integer(new Base(10));

        // then
        $this->assertSame(10, $integer);
    }
}

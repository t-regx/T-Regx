<?php
namespace Test\Feature\TRegx\CleanRegex\Match\Details\group\toInt;

use PHPUnit\Framework\TestCase;
use Test\Utils\ExactExceptionMessage;
use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\CleanRegex\Exception\IntegerFormatException;
use TRegx\CleanRegex\Exception\IntegerOverflowException;
use TRegx\CleanRegex\Match\Details\Detail;
use function pattern;

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
        $result = pattern('(?<name>-?\d+)')
            ->match($string)
            ->first(function (Detail $detail) {
                // when
                return $detail->group(1)->toInt();
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
    public function shouldParseIntBase4()
    {
        // given
        $result = pattern('(?<name>-?\d+)')->match('-321')->first(function (Detail $detail) {
            // when
            return $detail->group(1)->toInt(4);
        });

        // then
        $this->assertSame(-57, $result);
    }

    /**
     * @test
     */
    public function shouldThrow_forPseudoInteger_becausePhpSucks()
    {
        // then
        $this->expectException(IntegerFormatException::class);
        $this->expectExceptionMessage("Expected to parse group #1, but ' 10' is not a valid integer in base 10");

        // given
        pattern('(.*)', 's')
            ->match(' 10')
            ->first(function (Detail $detail) {
                // when
                return $detail->group(1)->toInt();
            });
    }

    /**
     * @test
     */
    public function shouldThrow_forInvalidInteger_base4()
    {
        // then
        $this->expectException(IntegerFormatException::class);
        $this->expectExceptionMessage("Expected to parse group #1, but '4' is not a valid integer in base 4");

        // given
        pattern('(4)')
            ->match('4')
            ->first(function (Detail $detail) {
                // when
                return $detail->group(1)->toInt(4);
            });
    }

    /**
     * @test
     */
    public function shouldParseInt_byName()
    {
        // given
        $result = pattern('(?<value>\d+)')
            ->match('12cm 14mm 13cm 19cm 18mm 2mm')
            ->map(function (Detail $detail) {
                // when
                return $detail->group('value')->toInt();
            });

        // then
        $this->assertSame([12, 14, 13, 19, 18, 2], $result);
    }

    /**
     * @test
     */
    public function shouldParseInt_byIndex()
    {
        // given
        $result = pattern('(?<value>\d+)')
            ->match('12cm 14mm 13cm 19cm 18mm 2mm')
            ->map(function (Detail $detail) {
                // when
                return $detail->group(1)->toInt();
            });

        // then
        $this->assertSame([12, 14, 13, 19, 18, 2], $result);
    }

    /**
     * @test
     */
    public function shouldThrow_forInvalidInteger_byName()
    {
        // then
        $this->expectException(IntegerFormatException::class);
        $this->expectExceptionMessage("Expected to parse group 'name', but 'Foo' is not a valid integer in base 10");

        // given
        pattern('(?<name>Foo)')
            ->match('Foo bar')
            ->first(function (Detail $detail) {
                // when
                return $detail->group('name')->toInt();
            });
    }

    /**
     * @test
     */
    public function shouldThrow_forInvalidInteger_byIndex()
    {
        // then
        $this->expectException(IntegerFormatException::class);
        $this->expectExceptionMessage("Expected to parse group #1, but 'Foo' is not a valid integer in base 10");

        // given
        pattern('(?<name>Foo)')
            ->match('Foo bar')
            ->first(function (Detail $detail) {
                // when
                return $detail->group(1)->toInt();
            });
    }

    /**
     * @test
     */
    public function shouldThrow_forOverflownInteger_byName()
    {
        // then
        $this->expectException(IntegerOverflowException::class);
        $this->expectExceptionMessage("Expected to parse group 'name', but '9223372036854775808' exceeds integer size on this architecture in base 10");

        // given
        pattern('(?<name>\d+)')
            ->match('9223372036854775808')
            ->first(function (Detail $detail) {
                // when
                return $detail->group('name')->toInt();
            });
    }

    /**
     * @test
     */
    public function shouldThrow_forOverflownInteger_byIndex()
    {
        // then
        $this->expectException(IntegerOverflowException::class);
        $this->expectExceptionMessage("Expected to parse group #1, but '-922337203685477580700' exceeds integer size on this architecture in base 10");

        // given
        pattern('(?<name>-\d+)')
            ->match('-922337203685477580700')
            ->first(function (Detail $detail) {
                // when
                return $detail->group(1)->toInt();
            });
    }

    /**
     * @test
     */
    public function shouldThrow_forOverflownInteger_inBase16()
    {
        // then
        $this->expectException(IntegerOverflowException::class);
        $this->expectExceptionMessage("Expected to parse group 'name', but '9223372036854775000' exceeds integer size on this architecture in base 16");

        // given
        pattern('(?<name>\d+)')
            ->match('9223372036854775000')
            ->first(function (Detail $detail) {
                // when
                return $detail->group('name')->toInt(16);
            });
    }

    /**
     * @test
     */
    public function shouldThrowForUnmatchedGroup()
    {
        // then
        $this->expectException(GroupNotMatchedException::class);
        $this->expectExceptionMessage("Expected to call toInt() for group 'missing', but the group was not matched");

        // given
        pattern('(?<name>Foo)(?<missing>\d+)?')
            ->match('Foo bar')
            ->first(function (Detail $detail) {
                // when
                return $detail->group('missing')->toInt();
            });
    }
}

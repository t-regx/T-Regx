<?php
namespace Test\Feature\TRegx\CleanRegex\Match\Details\group\parseInt;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Exception\CleanRegex\GroupNotMatchedException;
use TRegx\CleanRegex\Exception\CleanRegex\IntegerFormatException;
use TRegx\CleanRegex\Match\Details\Match;

class MatchImplTest extends TestCase
{
    /**
     * @test
     * @dataProvider validIntegers
     * @param string $string
     * @param int $expected
     */
    public function shouldParseInt(string $string, int $expected)
    {
        // given
        $result = pattern('(?<name>-?\w+)')
            ->match($string)
            ->first(function (Match $match) {
                // when
                return $match->group(1)->parseInt();
            });

        // then
        $this->assertEquals($expected, $result);
    }

    public function validIntegers()
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
    public function shouldThrow_forPseudoInteger_becausePhpSucks()
    {
        // then
        $this->expectException(IntegerFormatException::class);
        $this->expectExceptionMessage("Expected to parse group '1', but '1e3' is not a valid integer");

        // given
        pattern('(.*)', 's')
            ->match('1e3')
            ->first(function (Match $match) {
                // when
                return $match->group(1)->parseInt();
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
            ->map(function (Match $match) {
                // when
                return $match->group('value')->parseInt();
            });

        // then
        $this->assertEquals([12, 14, 13, 19, 18, 2], $result);
    }

    /**
     * @test
     */
    public function shouldParseInt_byIndex()
    {
        // given
        $result = pattern('(?<value>\d+)')
            ->match('12cm 14mm 13cm 19cm 18mm 2mm')
            ->map(function (Match $match) {
                // when
                return $match->group(1)->parseInt();
            });

        // then
        $this->assertEquals([12, 14, 13, 19, 18, 2], $result);
    }

    /**
     * @test
     */
    public function shouldThrow_forInvalidInteger_byName()
    {
        // then
        $this->expectException(IntegerFormatException::class);
        $this->expectExceptionMessage("Expected to parse group 'name', but 'Foo' is not a valid integer");

        // given
        pattern('(?<name>\w+)')
            ->match('Foo bar')
            ->first(function (Match $match) {
                // when
                return $match->group('name')->parseInt();
            });
    }

    /**
     * @test
     */
    public function shouldThrow_forInvalidInteger_byIndex()
    {
        // then
        $this->expectException(IntegerFormatException::class);
        $this->expectExceptionMessage("Expected to parse group '1', but 'Foo' is not a valid integer");

        // given
        pattern('(?<name>\w+)')
            ->match('Foo bar')
            ->first(function (Match $match) {
                // when
                return $match->group(1)->parseInt();
            });
    }

    /**
     * @test
     */
    public function shouldThrowForUnmatchedGroup()
    {
        // then
        $this->expectException(GroupNotMatchedException::class);
        $this->expectExceptionMessage("Expected to call parseInt() for group 'missing', but group was not matched");

        // given
        pattern('(?<name>\w+)(?<missing>\d+)?')
            ->match('Foo bar')
            ->first(function (Match $match) {
                // when
                return $match->group('missing')->parseInt();
            });
    }
}

<?php
namespace Test\Feature\TRegx\CleanRegex\Match\Details\parseInt;

use PHPUnit\Framework\TestCase;
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
        $result = pattern('-?\w+')
            ->match($string)
            ->first(function (Match $match) {
                // when
                return $match->parseInt();
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
        $this->expectExceptionMessage("Expected to parse '1e3', but it is not a valid integer");

        // given
        pattern('.*', 's')
            ->match('1e3')
            ->first(function (Match $match) {
                // when
                return $match->parseInt();
            });
    }

    /**
     * @test
     */
    public function shouldThrow_forInvalidInteger()
    {
        // then
        $this->expectException(IntegerFormatException::class);
        $this->expectExceptionMessage("Expected to parse 'Foo', but it is not a valid integer");

        // given
        pattern('\w+')
            ->match('Foo bar')
            ->first(function (Match $match) {
                // when
                return $match->parseInt();
            });
    }
}

<?php
namespace Test\Feature\TRegx\CleanRegex\Replace\Details\toInt;

use PHPUnit\Framework\TestCase;
use Test\Utils\ExactExceptionMessage;
use TRegx\CleanRegex\Exception\IntegerFormatException;
use TRegx\CleanRegex\Replace\Details\ReplaceDetail;
use function pattern;

class ReplacePatternTest extends TestCase
{
    use ExactExceptionMessage;

    /**
     * @test
     */
    public function shouldGetInteger()
    {
        // given
        pattern('194')
            ->replace('194')
            ->first()
            ->callback(function (ReplaceDetail $detail) {
                $this->assertSame(194, $detail->toInt());

                // cleanup
                return '';
            });
    }

    /**
     * @test
     */
    public function shouldGetIntegerBase11()
    {
        // given
        pattern('1abc')
            ->replace('1abc')
            ->first()
            ->callback(function (ReplaceDetail $detail) {
                $this->assertSame(4042, $detail->toInt(13));

                // cleanup
                return '';
            });
    }

    /**
     * @test
     */
    public function shouldThrowForInvalidArgumentBase9()
    {
        // given
        pattern('9')
            ->replace('9')
            ->first()
            ->callback(function (ReplaceDetail $detail) {
                // then
                $this->expectException(IntegerFormatException::class);
                $this->expectExceptionMessage("Expected to parse '9', but it is not a valid integer in base 9");

                // when
                $detail->toInt(9);

                // cleanup
                return '';
            });
    }
}

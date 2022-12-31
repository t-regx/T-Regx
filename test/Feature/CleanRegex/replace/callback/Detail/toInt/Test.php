<?php
namespace Test\Feature\CleanRegex\replace\callback\Detail\toInt;

use PHPUnit\Framework\TestCase;
use Test\Utils\Functions;
use Test\Utils\TestCase\TestCaseExactMessage;
use TRegx\CleanRegex\Exception\IntegerFormatException;
use TRegx\CleanRegex\Exception\IntegerOverflowException;
use function pattern;

class Test extends TestCase
{
    use TestCaseExactMessage;

    /**
     * @test
     */
    public function shouldGetInteger()
    {
        // given
        pattern('194')->replace('194')->callback(Functions::out($detail, ''));
        // when, then
        $this->assertSame(194, $detail->toInt());
    }

    /**
     * @test
     */
    public function shouldGetIntegerBase11()
    {
        // given
        pattern('1abc')->replace('1abc')->callback(Functions::out($detail, ''));
        // when, then
        $this->assertSame(4042, $detail->toInt(13));
    }

    /**
     * @test
     */
    public function shouldThrowForInvalidArgumentBase9()
    {
        // given
        pattern('9')->replace('9')->callback(Functions::out($detail, ''));
        // then
        $this->expectException(IntegerFormatException::class);
        $this->expectExceptionMessage("Expected to parse '9', but it is not a valid integer in base 9");
        // when
        $detail->toInt(9);
    }

    /**
     * @test
     */
    public function shouldThrow_forOverflownInteger()
    {
        // given
        pattern('-\d+')->replace('-9223372036854775809')->callback(Functions::out($detail, ''));
        // then
        $this->expectException(IntegerOverflowException::class);
        $this->expectExceptionMessage("Expected to parse '-9223372036854775809', but it exceeds integer size on this architecture in base 10");
        // when
        $detail->toInt();
    }
}

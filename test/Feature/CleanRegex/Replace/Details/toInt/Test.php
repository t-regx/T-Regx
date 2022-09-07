<?php
namespace Test\Feature\CleanRegex\Replace\Details\toInt;

use PHPUnit\Framework\TestCase;
use Test\Utils\DetailFunctions;
use Test\Utils\TestCase\TestCaseExactMessage;
use TRegx\CleanRegex\Exception\IntegerFormatException;
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
        pattern('194')->replace('194')->first()->callback(DetailFunctions::out($detail, ''));
        // when, then
        $this->assertSame(194, $detail->toInt());
    }

    /**
     * @test
     */
    public function shouldGetIntegerBase11()
    {
        // given
        pattern('1abc')->replace('1abc')->first()->callback(DetailFunctions::out($detail, ''));
        // when, then
        $this->assertSame(4042, $detail->toInt(13));
    }

    /**
     * @test
     */
    public function shouldThrowForInvalidArgumentBase9()
    {
        // given
        pattern('9')->replace('9')->first()->callback(DetailFunctions::out($detail, ''));
        // then
        $this->expectException(IntegerFormatException::class);
        $this->expectExceptionMessage("Expected to parse '9', but it is not a valid integer in base 9");
        // when
        $detail->toInt(9);
    }
}

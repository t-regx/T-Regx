<?php
namespace Test\Feature\CleanRegex\Match\stream\asInt;

use PHPUnit\Framework\TestCase;
use Test\Fakes\CleanRegex\Match\Details\ConstantInt;
use Test\Utils\DetailFunctions;
use Test\Utils\Functions;
use Test\Utils\TestCase\TestCaseExactMessage;
use TRegx\CleanRegex\Exception\IntegerFormatException;
use TRegx\CleanRegex\Exception\IntegerOverflowException;
use TRegx\CleanRegex\Exception\InvalidIntegerTypeException;
use TRegx\CleanRegex\Pattern;

/**
 * @covers \TRegx\CleanRegex\Internal\Match\Stream\IntegerStream
 */
class MatchPatternTest extends TestCase
{
    use TestCaseExactMessage;

    /**
     * @test
     */
    public function test()
    {
        // when
        $values = Pattern::of('Foo')
            ->match('Foo')
            ->stream()
            ->flatMap(Functions::constant(['1', 2, new ConstantInt(4, 2), '11']))
            ->asInt(2)
            ->all();
        // then
        $this->assertSame([1, 2, 4, 3], $values);
    }

    /**
     * @test
     */
    public function shouldAllThrowForMalformedInteger()
    {
        // given
        $stream = Pattern::of('\w+')->match('12, One')->stream()->map(DetailFunctions::text())->asInt();
        // then
        $this->expectException(IntegerFormatException::class);
        $this->expectExceptionMessage("Expected to parse stream element 'One', but it is not a valid integer in base 10");
        // when
        $stream->all();
    }

    /**
     * @test
     */
    public function shouldAllThrowForInvalidDataType()
    {
        // given
        $stream = Pattern::literal('Foo')->match('Foo')
            ->stream()
            ->map(Functions::constant(false))
            ->asInt();
        // then
        $this->expectException(InvalidIntegerTypeException::class);
        $this->expectExceptionMessage("Failed to parse value as integer. Expected integer|string, but boolean (false) given");
        // when
        $stream->all();
    }

    /**
     * @test
     */
    public function shouldGetFirstString()
    {
        // when
        $value = Pattern::literal('-123')->match('-123')->stream()->asInt()->first();
        // then
        $this->assertSame(-123, $value);
    }

    /**
     * @test
     */
    public function shouldGetFirstStringBase4()
    {
        // when
        $value = Pattern::literal('-123')->match('-123')->stream()->asInt(4)->first();
        // then
        $this->assertSame(-27, $value);
    }

    /**
     * @test
     */
    public function shouldGetFirstInteger()
    {
        // when
        $value = Pattern::literal('Foo')->match('Foo')->stream()->map(Functions::constant(1))->asInt()->first();
        // then
        $this->assertSame(1, $value);
    }

    /**
     * @test
     */
    public function shouldGetFirstIntable()
    {
        // when
        $value = Pattern::literal('Foo')->match('Foo')
            ->stream()
            ->map(Functions::constant(new ConstantInt(4, 11)))
            ->asInt(11)
            ->first();
        // then
        $this->assertSame(4, $value);
    }

    /**
     * @test
     */
    public function shouldFirstThrowForMalformedInteger()
    {
        // given
        $stream = Pattern::literal('Foo')->match('Foo')
            ->stream()
            ->map(DetailFunctions::text())
            ->asInt(14);
        // then
        $this->expectException(IntegerFormatException::class);
        $this->expectExceptionMessage("Expected to parse stream element 'Foo', but it is not a valid integer in base 14");
        // when
        $stream->first();
    }

    /**
     * @test
     */
    public function shouldFirstThrowForOverflownInteger()
    {
        // given
        $stream = Pattern::of('\d+')->match('9223372036854775809')
            ->stream()
            ->map(DetailFunctions::text())
            ->asInt();
        // then
        $this->expectException(IntegerOverflowException::class);
        $this->expectExceptionMessage("Expected to parse stream element '9223372036854775809', but it exceeds integer size on this architecture in base 10");
        // when
        $stream->first();
    }

    /**
     * @test
     */
    public function shouldFirstThrowForOverflownInteger_inBase16()
    {
        // given
        $stream = Pattern::of('\d+')->match('922337203685477580000')
            ->stream()
            ->map(DetailFunctions::text())
            ->asInt(16);
        // then
        $this->expectException(IntegerOverflowException::class);
        $this->expectExceptionMessage("Expected to parse stream element '922337203685477580000', but it exceeds integer size on this architecture in base 16");
        // when
        $stream->first();
    }

    /**
     * @test
     */
    public function shouldFirstThrowForInvalidDataType()
    {
        // given
        $stream = Pattern::literal('Foo')->match('Foo')
            ->stream()
            ->map(Functions::constant(true))
            ->asInt(16);
        // then
        $this->expectException(InvalidIntegerTypeException::class);
        $this->expectExceptionMessage("Failed to parse value as integer. Expected integer|string, but boolean (true) given");
        // when
        $stream->first();
    }

    /**
     * @test
     */
    public function shouldGetIdentityKey()
    {
        // when
        $key = Pattern::of('\d+')->match('14, 15')
            ->stream()
            ->filter(DetailFunctions::equals('15'))
            ->keys()
            ->first();
        // then
        $this->assertSame(1, $key);
    }
}

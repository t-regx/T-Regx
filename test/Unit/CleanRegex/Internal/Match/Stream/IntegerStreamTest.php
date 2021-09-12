<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Match\Stream;

use PHPUnit\Framework\TestCase;
use Test\Utils\ExactExceptionMessage;
use Test\Utils\Impl\AllStream;
use Test\Utils\Impl\ConstantInt;
use Test\Utils\Impl\FirstKeyStream;
use Test\Utils\Impl\FirstStream;
use Test\Utils\Impl\ThrowBase;
use TRegx\CleanRegex\Exception\FluentMatchPatternException;
use TRegx\CleanRegex\Exception\IntegerFormatException;
use TRegx\CleanRegex\Exception\IntegerOverflowException;
use TRegx\CleanRegex\Internal\Match\Stream\IntegerStream;
use TRegx\CleanRegex\Internal\Number\Base;

/**
 * @covers \TRegx\CleanRegex\Internal\Match\Stream\IntegerStream
 */
class IntegerStreamTest extends TestCase
{
    use ExactExceptionMessage;

    /**
     * @test
     */
    public function test()
    {
        // given
        $stream = new IntegerStream(new AllStream(['1', 2, new ConstantInt(4)]), new Base(2));

        // when
        $values = $stream->all();

        // then
        $this->assertSame([1, 2, 4], $values);
    }

    /**
     * @test
     */
    public function shouldAllThrowForMalformedInteger()
    {
        // given
        $stream = new IntegerStream(new AllStream(['Foo']), new Base(10));

        // then
        $this->expectException(IntegerFormatException::class);
        $this->expectExceptionMessage("Expected to parse fluent element 'Foo', but it is not a valid integer in base 10");

        // when
        $stream->all();
    }

    /**
     * @test
     */
    public function shouldAllThrowForInvalidDataType()
    {
        // given
        $stream = new IntegerStream(new AllStream([true]), new ThrowBase());

        // then
        $this->expectException(FluentMatchPatternException::class);
        $this->expectExceptionMessage("Invalid data types passed to asInt() method. Expected integer|string, but boolean (true) given");

        // when
        $stream->all();
    }

    /**
     * @test
     */
    public function shouldGetFirstString()
    {
        // given
        $stream = new IntegerStream(new FirstStream('-123'), new Base(10));

        // when
        $value = $stream->first();

        // then
        $this->assertSame(-123, $value);
    }

    /**
     * @test
     */
    public function shouldGetFirstStringBase4()
    {
        // given
        $stream = new IntegerStream(new FirstStream('-123'), new Base(4));

        // when
        $value = $stream->first();

        // then
        $this->assertSame(-27, $value);
    }

    /**
     * @test
     */
    public function shouldGetFirstInteger()
    {
        // given
        $stream = new IntegerStream(new FirstStream(1), new ThrowBase());

        // when
        $value = $stream->first();

        // then
        $this->assertSame(1, $value);
    }

    /**
     * @test
     */
    public function shouldGetFirstIntable()
    {
        // given
        $stream = new IntegerStream(new FirstStream(new ConstantInt(4)), new ThrowBase());

        // when
        $value = $stream->first();

        // then
        $this->assertSame(4, $value);
    }

    /**
     * @test
     */
    public function shouldFirstThrowForMalformedInteger()
    {
        // given
        $stream = new IntegerStream(new FirstStream('Foo'), new Base(14));

        // then
        $this->expectException(IntegerFormatException::class);
        $this->expectExceptionMessage("Expected to parse fluent element 'Foo', but it is not a valid integer in base 14");

        // when
        $stream->first();
    }

    /**
     * @test
     */
    public function shouldFirstThrowForOverflownInteger()
    {
        // given
        $stream = new IntegerStream(new FirstStream('9223372036854775809'), new Base(10));

        // then
        $this->expectException(IntegerOverflowException::class);
        $this->expectExceptionMessage("Expected to parse fluent element '9223372036854775809', but it exceeds integer size on this architecture in base 10");

        // when
        $stream->first();
    }

    /**
     * @test
     */
    public function shouldFirstThrowForOverflownInteger_inBase16()
    {
        // given
        $stream = new IntegerStream(new FirstStream('922337203685477580000'), new Base(16));

        // then
        $this->expectException(IntegerOverflowException::class);
        $this->expectExceptionMessage("Expected to parse fluent element '922337203685477580000', but it exceeds integer size on this architecture in base 16");

        // when
        $stream->first();
    }

    /**
     * @test
     */
    public function shouldFirstThrowForInvalidDataType()
    {
        // given
        $stream = new IntegerStream(new FirstStream(true), new ThrowBase());

        // then
        $this->expectException(FluentMatchPatternException::class);
        $this->expectExceptionMessage("Invalid data types passed to asInt() method. Expected integer|string, but boolean (true) given");

        // when
        $stream->first();
    }

    /**
     * @test
     */
    public function shouldGetIdentityKey()
    {
        // given
        $stream = new IntegerStream(new FirstKeyStream(4), new ThrowBase());

        // when
        $firstKey = $stream->firstKey();

        // then
        $this->assertSame(4, $firstKey);
    }
}
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
use TRegx\CleanRegex\Internal\Match\Stream\IntStream;
use TRegx\CleanRegex\Internal\Number\Base;

/**
 * @covers \TRegx\CleanRegex\Internal\Match\Stream\IntStream
 */
class IntStreamTest extends TestCase
{
    use ExactExceptionMessage;

    /**
     * @test
     */
    public function test()
    {
        // given
        $stream = new IntStream(new AllStream(['1', 2, new ConstantInt(4)]), new Base(2));

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
        $stream = new IntStream(new AllStream(['Foo']), new Base(10));

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
        $stream = new IntStream(new AllStream([true]), new ThrowBase());

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
        $stream = new IntStream(new FirstStream('-123'), new Base(10));

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
        $stream = new IntStream(new FirstStream('-123'), new Base(4));

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
        $stream = new IntStream(new FirstStream(1), new ThrowBase());

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
        $stream = new IntStream(new FirstStream(new ConstantInt(4)), new ThrowBase());

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
        $stream = new IntStream(new FirstStream('Foo'), new Base(14));

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
        $stream = new IntStream(new FirstStream('922337203685477580700'), new Base(10));

        // then
        $this->expectException(IntegerOverflowException::class);
        $this->expectExceptionMessage("Expected to parse fluent element '922337203685477580700', but it exceeds integer size on this architecture");

        // when
        $stream->first();
    }

    /**
     * @test
     */
    public function shouldFirstThrowForInvalidDataType()
    {
        // given
        $stream = new IntStream(new FirstStream(true), new ThrowBase());

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
        $stream = new IntStream(new FirstKeyStream(4), new ThrowBase());

        // when
        $firstKey = $stream->firstKey();

        // then
        $this->assertSame(4, $firstKey);
    }
}

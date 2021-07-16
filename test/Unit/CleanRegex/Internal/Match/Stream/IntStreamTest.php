<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Match\Stream;

use PHPUnit\Framework\TestCase;
use Test\Utils\Impl\AllStream;
use Test\Utils\Impl\ConstantInt;
use Test\Utils\Impl\FirstKeyStream;
use Test\Utils\Impl\FirstStream;
use TRegx\CleanRegex\Exception\FluentMatchPatternException;
use TRegx\CleanRegex\Exception\IntegerFormatException;
use TRegx\CleanRegex\Internal\Match\Stream\IntStream;

/**
 * @covers \TRegx\CleanRegex\Internal\Match\Stream\IntStream
 */
class IntStreamTest extends TestCase
{
    /**
     * @test
     */
    public function test()
    {
        // given
        $stream = new IntStream(new AllStream(['1', 2, new ConstantInt(4)]));

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
        $stream = new IntStream(new AllStream(['Foo']));

        // then
        $this->expectException(IntegerFormatException::class);
        $this->expectExceptionMessage("Expected to parse fluent element 'Foo', but it is not a valid integer");

        // when
        $stream->all();
    }

    /**
     * @test
     */
    public function shouldAllThrowForInvalidDataType()
    {
        // given
        $stream = new IntStream(new AllStream([true]));

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
        $stream = new IntStream(new FirstStream('1'));

        // when
        $value = $stream->first();

        // then
        $this->assertSame(1, $value);
    }

    /**
     * @test
     */
    public function shouldGetFirstInteger()
    {
        // given
        $stream = new IntStream(new FirstStream(1));

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
        $stream = new IntStream(new FirstStream(new ConstantInt(4)));

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
        $stream = new IntStream(new FirstStream('Foo'));

        // then
        $this->expectException(IntegerFormatException::class);
        $this->expectExceptionMessage("Expected to parse fluent element 'Foo', but it is not a valid integer");

        // when
        $stream->first();
    }

    /**
     * @test
     */
    public function shouldFirstThrowForInvalidDataType()
    {
        // given
        $stream = new IntStream(new FirstStream(true));

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
        $stream = new IntStream(new FirstKeyStream(4));

        // when
        $firstKey = $stream->firstKey();

        // then
        $this->assertSame(4, $firstKey);
    }
}

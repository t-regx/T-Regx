<?php
namespace Test\Feature\CleanRegex\Stream\asInt;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Test\Fakes\CleanRegex\Match\Details\ConstantInt;
use Test\Utils\ArrayStream;
use TRegx\CleanRegex\Exception\IntegerFormatException;
use TRegx\CleanRegex\Exception\IntegerOverflowException;
use TRegx\CleanRegex\Exception\InvalidIntegerTypeException;

/**
 * @covers \TRegx\CleanRegex\Match\Stream
 * @covers \TRegx\CleanRegex\Internal\Match\Stream\IntegerStream
 */
class StreamTest extends TestCase
{
    /**
     * @test
     */
    public function shouldParseInteger()
    {
        // given
        $stream = ArrayStream::of(['123']);
        // when
        $integers = $stream->asInt(10)->all();
        // then
        $this->assertSame([123], $integers);
    }

    /**
     * @test
     */
    public function shouldParseIntegerFirst()
    {
        // given
        $stream = ArrayStream::of(['-123', 'Malformed']);
        // when
        $integer = $stream->asInt(10)->first();
        // then
        $this->assertSame(-123, $integer);
    }

    /**
     * @test
     */
    public function shouldParseIntegerFirstBase4()
    {
        // when
        $integer = ArrayStream::of(['-123'])->asInt(4)->first();
        // then
        $this->assertSame(-27, $integer);
    }

    /**
     * @test
     */
    public function shouldAllThrowForMalformedInteger()
    {
        // given
        $stream = ArrayStream::of(['One'])->asInt();
        // then
        $this->expectException(IntegerFormatException::class);
        $this->expectExceptionMessage("Expected to parse stream element 'One', but it is not a valid integer in base 10");
        // when
        $stream->all();
    }

    /**
     * @test
     */
    public function shouldAllThrowForMalformedIntegerFirst()
    {
        // given
        $stream = ArrayStream::of(['One'])->asInt();
        // then
        $this->expectException(IntegerFormatException::class);
        $this->expectExceptionMessage("Expected to parse stream element 'One', but it is not a valid integer in base 10");
        // when
        $stream->first();
    }

    /**
     * @test
     */
    public function shouldAllThrowForInvalidDataType()
    {
        // given
        $stream = ArrayStream::of([false])->asInt();
        // then
        $this->expectException(InvalidIntegerTypeException::class);
        $this->expectExceptionMessage("Failed to parse value as integer. Expected integer|string, but boolean (false) given");
        // when
        $stream->all();
    }

    /**
     * @test
     */
    public function shouldAllThrowForInvalidDataTypeFirst()
    {
        // given
        $stream = ArrayStream::of([true])->asInt();
        // then
        $this->expectException(InvalidIntegerTypeException::class);
        $this->expectExceptionMessage("Failed to parse value as integer. Expected integer|string, but boolean (true) given");
        // when
        $stream->first();
    }

    /**
     * @test
     */
    public function shouldFirstThrowForOverflownInteger()
    {
        // given
        $stream = ArrayStream::of(['9223372036854775809'])->asInt();
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
        $stream = ArrayStream::of(['922337203685477580000'])->asInt(16);
        // then
        $this->expectException(IntegerOverflowException::class);
        $this->expectExceptionMessage("Expected to parse stream element '922337203685477580000', but it exceeds integer size on this architecture in base 16");
        // when
        $stream->first();
    }

    /**
     * @test
     */
    public function shouldGetIdentityKeys()
    {
        // given
        $stream = ArrayStream::of(['a' => '9', '10', 'b' => 11, '100']);
        // when
        $integers = $stream->asInt()->all();
        // then
        $this->assertSame(['a' => 9, 10, 'b' => 11, 100], $integers);
    }

    /**
     * @test
     */
    public function shouldGetIdentityKeyFirst()
    {
        // when
        $key = ArrayStream::of(['key' => 'value'])->keys()->first();
        // then
        $this->assertSame('key', $key);
    }

    /**
     * @test
     */
    public function shouldGetFirstIntable()
    {
        // given
        $stream = ArrayStream::of([new ConstantInt(4, 11)]);
        // when
        $integer = $stream->asInt(11)->first();
        // then
        $this->assertSame(4, $integer);
    }

    /**
     * @test
     * @dataProvider bases
     */
    public function shouldThrowForInvalidBase(int $base)
    {
        // given
        $stream = ArrayStream::empty();
        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Invalid base: $base (supported bases 2-36, case-insensitive)");
        // when
        $stream->asInt($base);
    }

    public function bases(): array
    {
        return [[-1], [0], [1], [37], [38]];
    }
}

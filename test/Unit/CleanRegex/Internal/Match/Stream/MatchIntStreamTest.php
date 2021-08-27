<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Match\Stream;

use PHPUnit\Framework\TestCase;
use Test\Utils\ExactExceptionMessage;
use Test\Utils\Impl\AllStreamBase;
use Test\Utils\Impl\FirstStreamBase;
use Test\Utils\Impl\ThrowBase;
use TRegx\CleanRegex\Exception\IntegerFormatException;
use TRegx\CleanRegex\Exception\IntegerOverflowException;
use TRegx\CleanRegex\Internal\Match\Stream\MatchIntStream;
use TRegx\CleanRegex\Internal\Number\Base;

/**
 * @covers \TRegx\CleanRegex\Internal\Match\Stream\MatchIntStream
 */
class MatchIntStreamTest extends TestCase
{
    use ExactExceptionMessage;

    /**
     * @test
     */
    public function shouldDelegate_all()
    {
        // given
        $stream = new MatchIntStream(AllStreamBase::texts(['14', '19', '25']), new Base(10));

        // when
        $all = $stream->all();

        // then
        $this->assertSame([14, 19, 25], $all);
    }

    /**
     * @test
     */
    public function shouldDelegate_all_unmatched()
    {
        // given
        $stream = new MatchIntStream(AllStreamBase::texts([]), new ThrowBase());

        // when
        $all = $stream->all();

        // then
        $this->assertSame([], $all);
    }

    /**
     * @test
     */
    public function shouldDelegate_first()
    {
        // given
        $stream = new MatchIntStream(FirstStreamBase::text('10101'), new Base(2));

        // when
        $first = $stream->first();

        // then
        $this->assertSame(21, $first);
    }

    /**
     * @test
     */
    public function shouldGetFirstLowerBound()
    {
        // given
        $stream = new MatchIntStream(FirstStreamBase::text('-2147483648'), new Base(10));

        // when
        $first = $stream->first();

        // then
        $this->assertSame(-2147483647 - 1, $first); // for some reason, PHP parses -2147483648 as float ;|
    }

    /**
     * @test
     */
    public function shouldNotDelegate_firstKey()
    {
        // given
        $stream = new MatchIntStream(FirstStreamBase::text('192'), new Base(10));

        // when
        $key = $stream->firstKey();

        // then
        $this->assertSame(0, $key);
    }

    /**
     * @test
     */
    public function shouldThrow_all_forMalformedInteger()
    {
        // given
        $stream = new MatchIntStream(AllStreamBase::texts(['Foo', 'Bar']), new Base(10));

        // then
        $this->expectException(IntegerFormatException::class);
        $this->expectExceptionMessage("Expected to parse 'Foo', but it is not a valid integer in base 10");

        // when
        $stream->all();
    }

    /**
     * @test
     */
    public function shouldThrow_first_forMalformedInteger()
    {
        // given
        $stream = new MatchIntStream(FirstStreamBase::text('Foo'), new Base(11));

        // then
        $this->expectException(IntegerFormatException::class);
        $this->expectExceptionMessage("Expected to parse 'Foo', but it is not a valid integer in base 11");

        // when
        $stream->first();
    }

    /**
     * @test
     */
    public function shouldThrow_firstKey_forMalformedInteger()
    {
        // given
        $stream = new MatchIntStream(FirstStreamBase::text('Foo'), new Base(13));

        // then
        $this->expectException(IntegerFormatException::class);
        $this->expectExceptionMessage("Expected to parse 'Foo', but it is not a valid integer in base 13");

        // when
        $stream->firstKey();
    }

    /**
     * @test
     */
    public function shouldThrow_firstKey_forOverflownInteger()
    {
        // given
        $stream = new MatchIntStream(FirstStreamBase::text('922337203685477580700'), new Base(10));

        // then
        $this->expectException(IntegerOverflowException::class);
        $this->expectExceptionMessage("Expected to parse '922337203685477580700', but it exceeds integer size on this architecture");

        // when
        $stream->firstKey();
    }
}

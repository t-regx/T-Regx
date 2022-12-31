<?php
namespace Test\Feature\CleanRegex\stream\limit\all;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Test\Utils\Stream\ArrayStream;

/**
 * @covers \TRegx\CleanRegex\Match\Stream
 * @covers \TRegx\CleanRegex\Internal\Match\Stream\LimitStream
 */
class StreamTest extends TestCase
{
    /**
     * @test
     */
    public function shouldLimit()
    {
        // when
        $stream = ArrayStream::of(['12', '15', '16', '19', '20']);
        // when
        $all = $stream->asInt()->limit(3)->all();
        // then
        $this->assertSame([12, 15, 16], $all);
    }

    /**
     * @test
     */
    public function shouldLimitAssoc()
    {
        // given
        $stream = ArrayStream::of([14 => 'one', 18 => 'two']);
        // when
        $all = $stream->limit(2)->all();
        // then
        $this->assertSame([14 => 'one', 18 => 'two'], $all);
    }

    /**
     * @test
     */
    public function shouldLimitUnderflow()
    {
        // when
        $all = ArrayStream::of([12, 15])->limit(4)->all();
        // then
        $this->assertSame([12, 15], $all);
    }

    /**
     * @test
     */
    public function shouldLimitUnmatched()
    {
        // when
        $empty = ArrayStream::unmatched()->limit(4)->all();
        // then
        $this->assertSame([], $empty);
    }

    /**
     * @test
     */
    public function shouldLimitEmpty()
    {
        // when
        $empty = ArrayStream::empty()->limit(4)->all();
        // then
        $this->assertSame([], $empty);
    }

    /**
     * @test
     */
    public function shouldThrowForNegativeOffsetOne()
    {
        // given
        $stream = ArrayStream::empty();
        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Negative limit: -1');
        // when
        $stream->limit(-1);
    }

    /**
     * @test
     */
    public function shouldThrowForNegativeOffsetThree()
    {
        // given
        $stream = ArrayStream::empty();
        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Negative limit: -3');
        // when
        $stream->limit(-3);
    }
}

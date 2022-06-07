<?php
namespace Test\Feature\CleanRegex\Stream\limit\keys;

use PHPUnit\Framework\TestCase;
use Test\Utils\ArrayStream;
use TRegx\CleanRegex\Exception\NoSuchStreamElementException;

/**
 * @covers \TRegx\CleanRegex\Match\Stream
 * @covers \TRegx\CleanRegex\Internal\Match\Stream\LimitStream
 */
class StreamTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetFirstKey()
    {
        // when
        $firstKey = ArrayStream::of(['Foo', 'Bar'])->limit(3)->keys()->first();
        // then
        $this->assertSame(0, $firstKey);
    }

    /**
     * @test
     */
    public function shouldGetFirstKeyAssoc()
    {
        // given
        $stream = ArrayStream::of(['one' => 'one', 'two' => 'two']);
        // when
        $firstKey = $stream->limit(2)->keys()->first();
        // then
        $this->assertSame('one', $firstKey);
    }

    /**
     * @test
     */
    public function shouldNotGetFirstKeyUnmatched()
    {
        // then
        $this->expectException(NoSuchStreamElementException::class);
        $this->expectExceptionMessage('Expected to get the first stream element, but the stream has 0 element(s)');
        // when
        ArrayStream::unmatched()->limit(2)->keys()->first();
    }

    /**
     * @test
     */
    public function shouldNotGetFirstKeyUnmatchedLimitZero()
    {
        // then
        $this->expectException(NoSuchStreamElementException::class);
        $this->expectExceptionMessage('Expected to get the first stream element, but the stream has 0 element(s)');
        // when
        ArrayStream::unmatched()->limit(0)->keys()->first();
    }

    /**
     * @test
     */
    public function shouldNotGetFirstKeyEmptyLimitOne()
    {
        // then
        $this->expectException(NoSuchStreamElementException::class);
        $this->expectExceptionMessage('Expected to get the first stream element, but the stream has 0 element(s)');
        // when
        ArrayStream::empty()->limit(1)->keys()->first();
    }

    /**
     * @test
     */
    public function shouldNotGetFirstKeyEmptyLimitFour()
    {
        // then
        $this->expectException(NoSuchStreamElementException::class);
        $this->expectExceptionMessage('Expected to get the first stream element, but the stream has 0 element(s)');
        // when
        ArrayStream::empty()->limit(4)->keys()->first();
    }

    /**
     * @test
     */
    public function shouldNotGetFirstKeyLimitZero()
    {
        // then
        $this->expectException(NoSuchStreamElementException::class);
        $this->expectExceptionMessage('Expected to get the first stream element, but the stream has 0 element(s)');
        // when
        ArrayStream::of(['Foo'])->limit(0)->keys()->first();
    }
}

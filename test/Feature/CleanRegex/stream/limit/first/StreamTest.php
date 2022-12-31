<?php
namespace Test\Feature\CleanRegex\stream\limit\first;

use PHPUnit\Framework\TestCase;
use Test\Utils\Stream\ArrayStream;
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
    public function shouldGetFirstLimitOne()
    {
        // when
        $first = ArrayStream::of(['Foo'])->limit(1)->first();
        // then
        $this->assertSame('Foo', $first);
    }

    /**
     * @test
     */
    public function shouldGetFirstLimitThree()
    {
        // when
        $first = ArrayStream::of(['Foo', 'Bar'])->limit(3)->first();
        // then
        $this->assertSame('Foo', $first);
    }

    /**
     * @test
     */
    public function shouldNotGetFirstUnmatched()
    {
        // then
        $this->expectException(NoSuchStreamElementException::class);
        $this->expectExceptionMessage('Expected to get the first match, but subject was not matched');
        // when
        ArrayStream::unmatched()->limit(2)->first();
    }

    /**
     * @test
     */
    public function shouldNotGetFirstEmpty()
    {
        // then
        $this->expectException(NoSuchStreamElementException::class);
        $this->expectExceptionMessage('Expected to get the first stream element, but the stream has 0 element(s)');
        // when
        ArrayStream::empty()->limit(2)->first();
    }

    /**
     * @test
     */
    public function shouldNotGetFirstEmptyLimitOne()
    {
        // then
        $this->expectException(NoSuchStreamElementException::class);
        $this->expectExceptionMessage('Expected to get the first stream element, but the stream has 0 element(s)');
        // when
        ArrayStream::empty()->limit(1)->first();
    }

    /**
     * @test
     */
    public function shouldNotGetFirstEmptyLimitFour()
    {
        // then
        $this->expectException(NoSuchStreamElementException::class);
        $this->expectExceptionMessage('Expected to get the first stream element, but the stream has 0 element(s)');
        // when
        ArrayStream::empty()->limit(4)->first();
    }

    /**
     * @test
     */
    public function shouldNotGetFirstLimitZero()
    {
        // then
        $this->expectException(NoSuchStreamElementException::class);
        $this->expectExceptionMessage('Expected to get the first stream element, but the stream has 0 element(s)');
        // when
        ArrayStream::empty()->limit(0)->first();
    }
}

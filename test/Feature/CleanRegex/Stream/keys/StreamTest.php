<?php
namespace Test\Feature\CleanRegex\Stream\keys;

use PHPUnit\Framework\TestCase;
use Test\Utils\ArrayStream;
use Test\Utils\Functions;
use Test\Utils\TestCasePasses;
use TRegx\CleanRegex\Exception\NoSuchStreamElementException;

/**
 * @covers \TRegx\CleanRegex\Match\Stream
 * @covers \TRegx\CleanRegex\Internal\Match\Stream\KeyStream
 */
class StreamTest extends TestCase
{
    use TestCasePasses;

    /**
     * @test
     */
    public function shouldGetAllKeys()
    {
        // when
        $keys = ArrayStream::of(['a' => 'One', 'b' => 'Two', 'c' => 'Three'])->keys()->all();
        // then
        $this->assertSame(['a', 'b', 'c'], $keys);
    }

    /**
     * @test
     */
    public function shouldGetKeysKeys()
    {
        // when
        $keysKeys = ArrayStream::of(['a' => 'One', 'b' => 'Two', 'c' => 'Three'])
            ->keys()
            ->keys()
            ->all();
        // then
        $this->assertSame([0, 1, 2], $keysKeys);
    }

    /**
     * @test
     */
    public function shouldGetFirstKey()
    {
        // when
        $firstKey = ArrayStream::of(['a' => 'One', 'b' => 'Two', 'c' => 'Three'])
            ->keys()
            ->first();
        // then
        $this->assertSame('a', $firstKey);
    }

    /**
     * @test
     */
    public function shouldGetFirstKeyKey()
    {
        // when
        $firstKey = ArrayStream::of(['a' => 'One', 'b' => 'Two', 'c' => 'Three'])
            ->keys()
            ->keys()
            ->first();
        // then
        $this->assertSame(0, $firstKey);
    }

    /**
     * @test
     */
    public function shouldKeysFirstThrowForUnmatchedStream()
    {
        // then
        $this->expectException(NoSuchStreamElementException::class);
        $this->expectExceptionMessage('Expected to get the first stream element, but the stream has 0 element(s)');
        // when
        ArrayStream::unmatched()->keys()->first();
    }

    /**
     * @test
     */
    public function shouldKeysKeysFirstThrowForUnmatchedStream()
    {
        // then
        $this->expectException(NoSuchStreamElementException::class);
        $this->expectExceptionMessage('Expected to get the first stream element, but the stream has 0 element(s)');
        // when
        ArrayStream::empty()->keys()->keys()->first();
    }

    /**
     * @test
     */
    public function shouldFirstKeyCallFirstFlatMap()
    {
        // when
        ArrayStream::of(['Foo'])
            ->flatMap(Functions::pass(['key']))
            ->keys()
            ->first();
    }

    /**
     * @test
     */
    public function shouldFirstKeyKeyCallFirstFlatMap()
    {
        // when
        ArrayStream::of(['Foo'])
            ->flatMap(Functions::pass(['key']))
            ->keys()
            ->keys()
            ->first();
    }

    /**
     * @test
     */
    public function shouldFirstKeyCallFirstMap()
    {
        // when
        ArrayStream::of(['Foo', 'Bar'])
            ->map(Functions::assertSame('Foo'))
            ->keys()
            ->first();
    }

    /**
     * @test
     */
    public function shouldFirstKeyKeyCallFirstMap()
    {
        // given
        $stream = ArrayStream::of(['Foo', 'Bar']);
        // when
        $stream->map(Functions::assertSame('Foo'))->keys()->keys()->first();
    }

    /**
     * @test
     */
    public function shouldKeysCallFlatMapOnce()
    {
        // when
        ArrayStream::of(['Foo'])
            ->flatMap(Functions::once(['key']))
            ->keys()
            ->first();
        // then
        $this->pass();
    }

    /**
     * @test
     */
    public function shouldKeysKeysCallFlatMapOnce()
    {
        // when
        ArrayStream::of(['Foo'])
            ->flatMap(Functions::once(['key']))
            ->keys()
            ->keys()
            ->first();
        // then
        $this->pass();
    }
}

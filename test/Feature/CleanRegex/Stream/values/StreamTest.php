<?php
namespace Test\Feature\CleanRegex\Stream\values;

use PHPUnit\Framework\TestCase;
use Test\Utils\Functions;
use Test\Utils\Stream\ArrayStream;
use TRegx\CleanRegex\Exception\NoSuchStreamElementException;

/**
 * @covers \TRegx\CleanRegex\Match\Stream
 * @covers \TRegx\CleanRegex\Internal\Match\Stream\ValueStream
 */
class StreamTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetValues()
    {
        // given
        $stream = ArrayStream::of([10 => 'One', 20 => 'Two', 30 => 'Three']);
        // when
        $values = $stream->values()->all();
        // then
        $this->assertSame(['One', 'Two', 'Three'], $values);
    }

    /**
     * @test
     */
    public function shouldGetFirst()
    {
        // given
        $stream = ArrayStream::of([10 => 'One', 20 => 'Two', 30 => 'Three']);
        // when
        $firstValue = $stream->values()->first();
        // then
        $this->assertSame('One', $firstValue);
    }

    /**
     * @test
     */
    public function shouldGetFirstKeyInteger()
    {
        // when
        $firstKey = ArrayStream::of([10 => 'One', 20 => 'Two', 30 => 'Three'])
            ->values()
            ->keys()
            ->first();
        // then
        $this->assertSame(0, $firstKey);
    }

    /**
     * @test
     */
    public function shouldGetFirstKeyString()
    {
        // given
        $stream = ArrayStream::of(['One' => 'One']);
        // when
        $firstKey = $stream->values()->keys()->first();
        // then
        $this->assertSame(0, $firstKey);
    }

    /**
     * @test
     */
    public function shouldFirstThrow()
    {
        // then
        $this->expectException(NoSuchStreamElementException::class);
        // when
        ArrayStream::of(['Foo'])
            ->flatMap(Functions::constant([]))
            ->values()
            ->first();
    }

    /**
     * @test
     */
    public function shouldFirstKeyThrow()
    {
        // then
        $this->expectException(NoSuchStreamElementException::class);
        // when
        ArrayStream::of(['Foo'])
            ->flatMap(Functions::constant([]))
            ->values()
            ->keys()
            ->first();
    }

    /**
     * @test
     */
    public function shouldCallPreviousFirstKey()
    {
        // when
        ArrayStream::of(['Foo'])
            ->flatMap(Functions::pass(['key']))
            ->values()
            ->keys()
            ->first();
    }
}

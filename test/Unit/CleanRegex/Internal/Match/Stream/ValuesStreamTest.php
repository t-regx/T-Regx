<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Match\Stream;

use PHPUnit\Framework\TestCase;
use Test\Fakes\CleanRegex\Internal\Match\Stream\FirstKeyStream;
use Test\Fakes\CleanRegex\Internal\Match\Stream\FirstStream;
use Test\Fakes\CleanRegex\Internal\Match\Stream\ThrowStream;
use Test\Fakes\CleanRegex\Internal\Match\Stream\Upstream\AllStream;
use TRegx\CleanRegex\Internal\Match\Stream\EmptyStreamException;
use TRegx\CleanRegex\Internal\Match\Stream\ValueStream;

/**
 * @covers \TRegx\CleanRegex\Internal\Match\Stream\ValueStream
 */
class ValuesStreamTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetAll()
    {
        // given
        $stream = new ValueStream(new AllStream([10 => 'One', 20 => 'Two', 30 => 'Three']));

        // when
        $all = $stream->all();

        // then
        $this->assertSame(['One', 'Two', 'Three'], $all);
    }

    /**
     * @test
     */
    public function shouldGetFirst()
    {
        // given
        $stream = new ValueStream(new FirstStream('One'));

        // when
        $first = $stream->first();

        // then
        $this->assertSame('One', $first);
    }

    /**
     * @test
     */
    public function shouldGetFirstKey()
    {
        // given
        $stream = new ValueStream(new FirstKeyStream('foo'));

        // when
        $firstKey = $stream->firstKey();

        // then
        $this->assertSame('foo', $firstKey);
    }

    /**
     * @test
     */
    public function shouldFirstThrow()
    {
        // given
        $stream = new ValueStream(new ThrowStream(new EmptyStreamException()));

        // then
        $this->expectException(EmptyStreamException::class);

        // when
        $stream->first();
    }
}

<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Match\Stream;

use PHPUnit\Framework\TestCase;
use Test\Fakes\CleanRegex\Internal\Match\Stream\FirstKeyStream;
use Test\Fakes\CleanRegex\Internal\Match\Stream\FirstStream;
use Test\Fakes\CleanRegex\Internal\Match\Stream\ThrowStream;
use Test\Fakes\CleanRegex\Internal\Match\Stream\Upstream\AllStream;
use Test\Utils\Functions;
use TRegx\CleanRegex\Internal\Match\Stream\ArrayOnlyStream;
use TRegx\CleanRegex\Internal\Match\Stream\EmptyStreamException;

/**
 * @covers \TRegx\CleanRegex\Internal\Match\Stream\ArrayOnlyStream
 */
class ArrayOnlyStreamTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetAll()
    {
        // given
        $stream = new ArrayOnlyStream(new AllStream([10 => 'One', 20 => 'Two', 30 => 'Three']), 'array_values');

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
        $stream = new ArrayOnlyStream(new FirstStream('One'), 'strToUpper');

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
        $stream = new ArrayOnlyStream(new FirstKeyStream('foo'), Functions::fail());

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
        $stream = new ArrayOnlyStream(new ThrowStream(new EmptyStreamException()), 'strLen');

        // then
        $this->expectException(EmptyStreamException::class);

        // when
        $stream->first();
    }
}

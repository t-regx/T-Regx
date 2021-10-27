<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Match\Stream;

use PHPUnit\Framework\TestCase;
use Test\Fakes\CleanRegex\Internal\Match\Stream\FirstKeyStream;
use Test\Fakes\CleanRegex\Internal\Match\Stream\FirstStream;
use Test\Fakes\CleanRegex\Internal\Match\Stream\ThrowStream;
use Test\Fakes\CleanRegex\Internal\Match\Stream\Upstream\AllStream;
use Test\Utils\Functions;
use TRegx\CleanRegex\Internal\Match\Stream\EmptyStreamException;
use TRegx\CleanRegex\Internal\Match\Stream\MapStream;

/**
 * @covers \TRegx\CleanRegex\Internal\Match\Stream\MapStream
 */
class MapStreamTest extends TestCase
{
    /**
     * @test
     */
    public function shouldReturn_all()
    {
        // given
        $stream = new MapStream(new AllStream(['One', 'Two', 'Three']), 'strToUpper');

        // when
        $all = $stream->all();

        // then
        $this->assertSame(['ONE', 'TWO', 'THREE'], $all);
    }

    /**
     * @test
     */
    public function shouldReturn_first()
    {
        // given
        $stream = new MapStream(new FirstStream('One'), function (string $element) {
            $this->assertSame('One', $element, 'Failed to assert that callback is only called for the first element');
            return 'foo';
        });

        // when
        $first = $stream->first();

        // then
        $this->assertSame('foo', $first);
    }

    /**
     * @test
     */
    public function shouldReturn_firstKey_dataTypeString()
    {
        // given
        $stream = new MapStream(new FirstKeyStream('foo'), Functions::fail());

        // when
        $firstKey = $stream->firstKey();

        // then
        $this->assertSame('foo', $firstKey);
    }

    /**
     * @test
     */
    public function shouldReturn_first_dataTypeInteger()
    {
        // given
        $stream = new MapStream(new FirstStream(1), Functions::identity());

        // when
        $first = $stream->first();

        // then
        $this->assertSame(1, $first);
    }

    /**
     * @test
     */
    public function shouldRethrow_first()
    {
        // given
        $stream = new MapStream(new ThrowStream(new EmptyStreamException()), 'strToUpper');

        // then
        $this->expectException(EmptyStreamException::class);

        // when
        $stream->first();
    }
}

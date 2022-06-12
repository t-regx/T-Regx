<?php
namespace Test\Feature\CleanRegex\Stream\count;

use PHPUnit\Framework\TestCase;
use Test\Utils\ArrayStream;

/**
 * @covers \TRegx\CleanRegex\Match\Stream
 * @covers \TRegx\CleanRegex\Internal\Match\Stream\StreamTerminal
 */
class StreamTest extends TestCase
{
    /**
     * @test
     */
    public function shouldCount()
    {
        // given
        $stream = ArrayStream::of(['Neo', 'Cypher', 'Trinity', 'Tank']);
        // when
        $count = $stream->count();
        // then
        $this->assertSame(4, $count);
    }

    /**
     * @test
     */
    public function shouldCountEmpty()
    {
        // given
        $stream = ArrayStream::empty();
        // when
        $count = $stream->count();
        // then
        $this->assertSame(0, $count);
    }

    /**
     * @test
     */
    public function shouldCountUnmatched()
    {
        // given
        $stream = ArrayStream::unmatched();
        // when
        $count = $stream->count();
        // then
        $this->assertSame(0, $count);
    }
}

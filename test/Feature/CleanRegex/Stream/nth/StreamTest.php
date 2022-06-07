<?php
namespace Test\Feature\CleanRegex\Stream\nth;

use PHPUnit\Framework\TestCase;
use Test\Utils\ArrayStream;

/**
 * @covers \TRegx\CleanRegex\Match\Stream
 */
class StreamTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetNth()
    {
        // given
        $stream = ArrayStream::of(['Neo', 'Cypher', 'Trinity', 'Tank']);
        // when
        $nth = $stream->nth(2);
        // then
        $this->assertSame('Trinity', $nth);
    }

    /**
     * @test
     */
    public function shouldThrow_forNegativeIndex()
    {
        // given
        $stream = ArrayStream::empty();
        // then
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Negative index: -1');
        // when
        $stream->nth(-1);
    }

    /**
     * @test
     */
    public function shouldThrow_forNegativeIndex_negative3()
    {
        // given
        $stream = ArrayStream::empty();
        // then
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Negative index: -3');
        // when
        $stream->nth(-3);
    }
}

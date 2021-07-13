<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Match\Stream;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\Match\Stream\KeysStream;
use TRegx\CleanRegex\Internal\Match\Stream\Stream;

/**
 * @covers \TRegx\CleanRegex\Internal\Match\Stream\KeysStream
 */
class KeysStreamTest extends TestCase
{
    /**
     * @test
     */
    public function shouldReturn_all()
    {
        // given
        $stream = new KeysStream($this->mock('all', ['a' => 'One', 'b' => 'Two', 'c' => 'Three']));

        // when
        $keys = $stream->all();

        // then
        $this->assertSame(['a', 'b', 'c'], $keys);
    }

    /**
     * @test
     */
    public function shouldReturn_first()
    {
        // given
        $stream = new KeysStream($this->mock('firstKey', 'One'));

        // when
        $first = $stream->first();

        // then
        $this->assertSame('One', $first);
    }

    /**
     * @test
     */
    public function shouldReturn_firstKey_beAlwaysZero()
    {
        // given
        $stream = new KeysStream($this->zeroInteraction());

        // when
        $firstKey = $stream->firstKey();

        // then
        $this->assertSame(0, $firstKey);
    }

    private function mock(string $methodName, $value): Stream
    {
        /** @var Stream|MockObject $stream */
        $stream = $this->createMock(Stream::class);
        $stream->expects($this->once())->method($methodName)->willReturn($value);
        $stream->expects($this->never())->method($this->logicalNot($this->matches($methodName)));
        return $stream;
    }

    private function zeroInteraction(): Stream
    {
        /** @var Stream|MockObject $base */
        $base = $this->createMock(Stream::class);
        $base->expects($this->never())->method($this->anything());
        return $base;
    }
}

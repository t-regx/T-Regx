<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Match\Stream;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\Exception\NoFirstStreamException;
use TRegx\CleanRegex\Internal\Match\Stream\MappingStream;
use TRegx\CleanRegex\Internal\Match\Stream\Stream;

class MappingStreamTest extends TestCase
{
    /**
     * @test
     */
    public function shouldMapAll()
    {
        // given
        $stream = new MappingStream($this->mock('all', 'willReturn', ['One', 'Two', 'Three']), 'strtoupper');

        // when
        $all = $stream->all();

        // then
        $this->assertSame(['ONE', 'TWO', 'THREE'], $all);
    }

    /**
     * @test
     */
    public function shouldGetFirst()
    {
        // given
        $stream = new MappingStream($this->mock('first', 'willReturn', 'One'), function (string $element) {
            $this->assertEquals('One', $element, 'Failed to assert that callback is only called for the first element');
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
    public function shouldGetFirstKey()
    {
        // given
        $stream = new MappingStream($this->mock('firstKey', 'willReturn', 'foo'), [$this, 'fail']);

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
        $stream = new MappingStream($this->mock('first', 'willThrowException', new NoFirstStreamException()), 'strtoupper');

        // then
        $this->expectException(NoFirstStreamException::class);

        // when
        $stream->first();
    }

    /**
     * @test
     */
    public function shouldFirstReturnInteger()
    {
        // given
        $stream = new MappingStream($this->mock('first', 'willReturn', 1), function (int $a) {
            return $a;
        });

        // when
        $first = $stream->first();

        // then
        $this->assertSame(1, $first);
    }

    private function mock(string $methodName, string $setter, $value): Stream
    {
        /** @var Stream|MockObject $stream */
        $stream = $this->createMock(Stream::class);
        $stream->expects($this->once())->method($methodName)->$setter($value);
        $stream->expects($this->never())->method($this->logicalNot($this->matches($methodName)));
        return $stream;
    }
}

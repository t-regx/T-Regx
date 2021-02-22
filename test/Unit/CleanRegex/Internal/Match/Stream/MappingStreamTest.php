<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Match\Stream;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Test\Utils\Functions;
use TRegx\CleanRegex\Internal\Exception\NoFirstStreamException;
use TRegx\CleanRegex\Internal\Match\Stream\MappingStream;
use TRegx\CleanRegex\Internal\Match\Stream\Stream;

class MappingStreamTest extends TestCase
{
    /**
     * @test
     */
    public function shouldReturn_all()
    {
        // given
        $stream = new MappingStream($this->mock('all', 'willReturn', ['One', 'Two', 'Three']), 'strToUpper');

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
        $stream = new MappingStream($this->mock('first', 'willReturn', 'One'), function (string $element) {
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
        $stream = new MappingStream($this->mock('firstKey', 'willReturn', 'foo'), Functions::fail());

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
        $stream = new MappingStream($this->mock('first', 'willReturn', 1), Functions::identity());

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
        $stream = new MappingStream($this->mock('first', 'willThrowException', new NoFirstStreamException()), 'strToUpper');

        // then
        $this->expectException(NoFirstStreamException::class);

        // when
        $stream->first();
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

<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Match\Stream;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Exception\InvalidReturnValueException;
use TRegx\CleanRegex\Internal\Exception\NoFirstStreamException;
use TRegx\CleanRegex\Internal\Match\Stream\FlatMappingStream;
use TRegx\CleanRegex\Internal\Match\Stream\Stream;

class FlatMappingStreamTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetAll()
    {
        // given
        $stream = new FlatMappingStream($this->mock('all', 'willReturn', ['One', 'Two', 'Three']), 'str_split');

        // when
        $all = $stream->all();

        // then
        $this->assertSame(['O', 'n', 'e', 'T', 'w', 'o', 'T', 'h', 'r', 'e', 'e'], $all);
    }

    /**
     * @test
     */
    public function shouldGetFirst()
    {
        // given
        $stream = new FlatMappingStream($this->mock('first', 'willReturn', 'One'), 'str_split');

        // when
        $first = $stream->first();

        // then
        $this->assertSame(['O', 'n', 'e'], $first);
    }

    /**
     * @test
     */
    public function shouldGetFirstKey()
    {
        // given
        $stream = new FlatMappingStream($this->mock('firstKey', 'willReturn', 'foo'), [$this, 'fail']);

        // when
        $firstKey = $stream->firstKey();

        // then
        $this->assertSame('foo', $firstKey);
    }

    /**
     * @test
     */
    public function shouldFirstThrow_forNoFirstElement()
    {
        // given
        $stream = new FlatMappingStream($this->mock('first', 'willThrowException', new NoFirstStreamException()), 'strlen');

        // then
        $this->expectException(NoFirstStreamException::class);

        // when
        $stream->first();
    }

    /**
     * @test
     */
    public function shouldReturn_forEmptyArray()
    {
        // given
        $stream = new FlatMappingStream($this->mock('first', 'willReturn', []), function (array $arg) {
            return $arg;
        });

        // when
        $first = $stream->first();

        // then
        $this->assertSame([], $first);
    }

    /**
     * @test
     */
    public function shouldFirstThrow_invalidReturnType()
    {
        // given
        $stream = new FlatMappingStream($this->mock('first', 'willReturn', 'Foo'), 'strlen');

        // then
        $this->expectException(InvalidReturnValueException::class);

        // when
        $stream->first();
    }

    /**
     * @test
     */
    public function shouldAllThrow_invalidReturnType()
    {
        // given
        $stream = new FlatMappingStream($this->mock('all', 'willReturn', ['Foo']), 'strlen');

        // then
        $this->expectException(InvalidReturnValueException::class);

        // when
        $stream->all();
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

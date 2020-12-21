<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Match\Stream;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Test\Utils\Functions;
use Test\Utils\ReverseFlatMap;
use Test\Utils\ThrowFlatMap;
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
        $stream = new FlatMappingStream($this->mock('all', 'willReturn', ['One', 'Two', 'Three']), new ReverseFlatMap(), 'str_split', '');

        // when
        $all = $stream->all();

        // then
        $this->assertSame(['e', 'e', 'r', 'h', 'T', 'o', 'w', 'T', 'e', 'n', 'O'], $all);
    }

    /**
     * @test
     */
    public function shouldGetFirst()
    {
        // given
        $stream = new FlatMappingStream($this->mock('first', 'willReturn', 'One'), new ThrowFlatMap(), 'str_split', '');

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
        $stream = new FlatMappingStream($this->mock('firstKey', 'willReturn', 'foo'), new ThrowFlatMap(), Functions::fail(), '');

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
        $stream = new FlatMappingStream($this->mock('first', 'willThrowException', new NoFirstStreamException()), new ThrowFlatMap(), 'strlen', '');

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
        $stream = new FlatMappingStream($this->mock('first', 'willReturn', []), new ThrowFlatMap(), Functions::identity(), '');

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
        $stream = new FlatMappingStream($this->mock('first', 'willReturn', 'Book'), new ThrowFlatMap(), 'strlen', 'lorem');

        // then
        $this->expectException(InvalidReturnValueException::class);
        $this->expectExceptionMessage('Invalid lorem() callback return type. Expected array, but integer (4) given');

        // when
        $stream->first();
    }

    /**
     * @test
     */
    public function shouldAllThrow_invalidReturnType()
    {
        // given
        $stream = new FlatMappingStream($this->mock('all', 'willReturn', ['Foo']), new ThrowFlatMap(), 'strlen', 'hello');

        // then
        $this->expectException(InvalidReturnValueException::class);
        $this->expectExceptionMessage('Invalid hello() callback return type. Expected array, but integer (3) given');

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

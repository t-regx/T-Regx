<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Match\first;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Test\Utils\CustomException;
use Test\Utils\Functions;
use Test\Utils\Impl\ThrowWorker;
use TRegx\CleanRegex\Internal\Exception\NoFirstStreamException;
use TRegx\CleanRegex\Internal\Match\Stream\Stream;
use TRegx\CleanRegex\Match\FluentMatchPattern;

class FluentMatchPatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetFirst()
    {
        // given
        $pattern = new FluentMatchPattern($this->stream('first', 'foo'), ThrowWorker::none());

        // when
        $result = $pattern->first();

        // then
        $this->assertSame('foo', $result);
    }

    /**
     * @test
     */
    public function shouldGetValuesFirst()
    {
        // given
        $pattern = new FluentMatchPattern($this->stream('first', 'foo'), ThrowWorker::none());

        // when
        $result = $pattern->values()->first();

        // then
        $this->assertSame('foo', $result);
    }

    /**
     * @test
     */
    public function shouldInvoke_consumer()
    {
        // given
        $pattern = new FluentMatchPattern($this->stream('first', 'foo'), ThrowWorker::none());

        // when
        $pattern->first(function ($value) {
            // then
            $this->assertSame('foo', $value);
        });
    }

    /**
     * @test
     */
    public function shouldThrowEmpty()
    {
        // given
        $pattern = new FluentMatchPattern($this->unmatchedMock(), ThrowWorker::fluent(new CustomException('message')));

        // then
        $this->expectException(CustomException::class);
        $this->expectExceptionMessage('message');

        // when
        $pattern->first();
    }

    /**
     * @test
     */
    public function shouldThrowEmpty_consumer()
    {
        // given
        $pattern = new FluentMatchPattern($this->unmatchedMock(), ThrowWorker::fluent(new CustomException('message')));

        // then
        $this->expectException(CustomException::class);
        $this->expectExceptionMessage('message');

        // when
        $pattern->first(Functions::fail());
    }

    private function stream(string $method, $return): Stream
    {
        /** @var Stream|MockObject $stream */
        $stream = $this->createMock(Stream::class);
        $stream->expects($this->once())->method($method)->willReturn($return);
        $stream->expects($this->never())->method($this->logicalNot($this->matches($method)));
        return $stream;
    }

    private function unmatchedMock(): Stream
    {
        /** @var Stream|MockObject $stream */
        $stream = $this->createMock(Stream::class);
        $stream->expects($this->once())->method('first')->willThrowException(new NoFirstStreamException());
        $stream->expects($this->never())->method($this->logicalNot($this->matches('first')));
        return $stream;
    }
}

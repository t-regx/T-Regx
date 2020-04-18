<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Match\first;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Test\Utils\Functions;
use TRegx\CleanRegex\Exception\NoSuchElementFluentException;
use TRegx\CleanRegex\Internal\Exception\Messages\NotMatchedMessage;
use TRegx\CleanRegex\Internal\Exception\NoFirstStreamException;
use TRegx\CleanRegex\Internal\Factory\FluentOptionalWorker;
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
        $pattern = new FluentMatchPattern($this->stream('first', 'foo'), $this->worker(''));

        // when
        $result = $pattern->first();

        // then
        $this->assertEquals('foo', $result);
    }

    /**
     * @test
     */
    public function shouldGetValuesFirst()
    {
        // given
        $pattern = new FluentMatchPattern($this->stream('first', 'foo'), $this->worker(''));

        // when
        $result = $pattern->values()->first();

        // then
        $this->assertEquals('foo', $result);
    }

    /**
     * @test
     */
    public function shouldGetKeysFirst()
    {
        // given
        $pattern = new FluentMatchPattern($this->stream('firstKey', 4), $this->worker(''));

        // when
        $result = $pattern->keys()->first();

        // then
        $this->assertSame(4, $result);
    }

    /**
     * @test
     */
    public function shouldInvoke_consumer()
    {
        // given
        $pattern = new FluentMatchPattern($this->stream('first', 'foo'), $this->worker(''));

        // when
        $pattern->first(function ($value, $key = null) {
            // then
            $this->assertEquals('foo', $value);
            $this->assertNull($key); // For now, `first()` won't receive key as a second argument
        });
    }

    /**
     * @test
     */
    public function shouldThrowEmpty()
    {
        // given
        $pattern = new FluentMatchPattern($this->unmatchedMock(), $this->worker('Exception message'));

        // then
        $this->expectException(NoSuchElementFluentException::class);
        $this->expectExceptionMessage('Exception message');

        // when
        $pattern->first();
    }

    /**
     * @test
     */
    public function shouldThrowEmpty_consumer()
    {
        // given
        $pattern = new FluentMatchPattern($this->unmatchedMock(), $this->worker('Exception message'));

        // then
        $this->expectException(NoSuchElementFluentException::class);
        $this->expectExceptionMessage('Exception message');

        // when
        $pattern->first(Functions::fail());
    }

    private function worker(string $message): FluentOptionalWorker
    {
        /** @var FluentOptionalWorker|MockObject $mockObject */
        $mockObject = $this->createMock(FluentOptionalWorker::class);
        $mock = $this->createMock(NotMatchedMessage::class);
        $mock->method('getMessage')->willReturn($message);
        $mockObject->method('noFirstElementException')->willReturn(NoSuchElementFluentException::withMessage($mock));
        return $mockObject;
    }

    private function stream(string $method, $return, int $times = 1): Stream
    {
        /** @var Stream|MockObject $stream */
        $stream = $this->createMock(Stream::class);
        $stream->expects($this->exactly($times))->method($method)->willReturn($return);
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

<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Match\_all_unmatched;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Test\Utils\Functions;
use Test\Utils\Impl\ThrowWorker;
use TRegx\CleanRegex\Internal\Exception\UnmatchedStreamException;
use TRegx\CleanRegex\Internal\Match\Stream\Stream;
use TRegx\CleanRegex\Match\FluentMatchPattern;

class FluentMatchPatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGet_all()
    {
        // given
        $pattern = new FluentMatchPattern($this->unmatched(), ThrowWorker::none());

        // when
        $all = $pattern->all();

        // then
        $this->assertSame([], $all);
    }

    /**
     * @test
     */
    public function shouldGet_only()
    {
        // given
        $pattern = new FluentMatchPattern($this->unmatched(), ThrowWorker::none());

        // when
        $all = $pattern->only(2);

        // then
        $this->assertSame([], $all);
    }

    /**
     * @test
     */
    public function shouldThrow_only_ForNegativeIndex()
    {
        // given
        $pattern = new FluentMatchPattern($this->unmatched(0), ThrowWorker::none());

        // then
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Negative limit: -2');

        // when
        $pattern->only(-2);
    }

    /**
     * @test
     */
    public function shouldIgnore_forEach()
    {
        // given
        $pattern = new FluentMatchPattern($this->unmatched(), ThrowWorker::none());

        // when
        $pattern->forEach(Functions::fail());

        // then
        $this->assertTrue(true);
    }

    /**
     * @test
     */
    public function shouldPassThrough_forEach()
    {
        // given
        $pattern = new FluentMatchPattern($this->stream(1), ThrowWorker::none());

        // then
        $this->expectException(UnmatchedStreamException::class);

        // when
        $pattern->forEach(Functions::throws(new UnmatchedStreamException()));
    }

    /**
     * @test
     */
    public function shouldGet_count()
    {
        // given
        $pattern = new FluentMatchPattern($this->unmatched(), ThrowWorker::none());

        // when
        $count = $pattern->count();

        // then
        $this->assertSame(0, $count);
    }

    /**
     * @test
     */
    public function shouldGet_getIterator()
    {
        // given
        $pattern = new FluentMatchPattern($this->unmatched(), ThrowWorker::none());

        // when
        $iterator = $pattern->getIterator();

        // then
        $this->assertSame([], iterator_to_array($iterator));
    }

    private function stream(int $times = 1): Stream
    {
        /** @var Stream|MockObject $stream */
        $stream = $this->createMock(Stream::class);
        $stream->expects($this->exactly($times))->method('all')->willReturn(['value']);
        $stream->expects($this->never())->method($this->logicalNot($this->matches('all')));
        return $stream;
    }

    private function unmatched(int $times = 1): Stream
    {
        /** @var Stream|MockObject $stream */
        $stream = $this->createMock(Stream::class);
        $stream->expects($this->exactly($times))->method('all')->willThrowException(new UnmatchedStreamException());
        $stream->expects($this->never())->method($this->logicalNot($this->matches('all')));
        return $stream;
    }
}

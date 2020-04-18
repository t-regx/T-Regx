<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Match\findFirst;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Test\Utils\CustomSubjectException;
use Test\Utils\Functions;
use TRegx\CleanRegex\Exception\NoSuchElementFluentException;
use TRegx\CleanRegex\Internal\Exception\Messages\FirstFluentMessage;
use TRegx\CleanRegex\Internal\Exception\NoFirstStreamException;
use TRegx\CleanRegex\Internal\Factory\FluentOptionalWorker;
use TRegx\CleanRegex\Internal\Match\Stream\Stream;
use TRegx\CleanRegex\Match\FluentMatchPattern;

class FluentMatchPatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldFindFirst()
    {
        // given
        $pattern = new FluentMatchPattern($this->firstStream('FOO', 3), $this->worker());

        // when
        $result1 = $pattern->findFirst('strtoupper')->orReturn('');
        $result2 = $pattern->findFirst('strtoupper')->orElse('strtolower');
        $result3 = $pattern->findFirst('strtoupper')->orThrow();

        // then
        $this->assertEquals('FOO', $result1);
        $this->assertEquals('FOO', $result2);
        $this->assertEquals('FOO', $result3);
    }

    /**
     * @test
     */
    public function shouldFindFirst_throwEmpty()
    {
        // given
        $pattern = new FluentMatchPattern($this->unmatchedMock(), $this->worker());

        // then
        $this->expectException(NoSuchElementFluentException::class);
        $this->expectExceptionMessage("Expected to get the first element from fluent pattern, but the elements feed is empty");

        // when
        $pattern->findFirst('strtoupper')->orThrow();
    }

    /**
     * @test
     */
    public function shouldFindFirst_orReturn()
    {
        // given
        $pattern = new FluentMatchPattern($this->unmatchedMock(), $this->worker());

        // when
        $result = $pattern->findFirst('strtoupper')->orReturn('otherValue');

        // then
        $this->assertEquals('otherValue', $result);
    }

    /**
     * @test
     */
    public function shouldFindFirst_orElse()
    {
        // given
        $pattern = new FluentMatchPattern($this->unmatchedMock(), $this->worker());

        // when
        $result = $pattern->findFirst('strtoupper')->orElse(Functions::constant('otherValue'));

        // then
        $this->assertEquals('otherValue', $result);
    }

    /**
     * @test
     */
    public function shouldFindFirst_orElse_notPassArguments()
    {
        // given
        $pattern = new FluentMatchPattern($this->unmatchedMock(), $this->worker());

        // when
        $pattern->findFirst('strtoupper')->orElse(function () {
            // when
            $arguments = func_get_args();

            // then
            $this->assertEmpty($arguments);
        });
    }

    /**
     * @test
     */
    public function shouldFindFirst_throwEmpty_custom()
    {
        // given
        $pattern = new FluentMatchPattern($this->unmatchedMock(), $this->worker());

        // then
        $this->expectException(CustomSubjectException::class);
        $this->expectExceptionMessage("Expected to get the first element from fluent pattern, but the elements feed is empty");

        // when
        $pattern->findFirst('strtoupper')->orThrow(CustomSubjectException::class);
    }

    private function worker(): FluentOptionalWorker
    {
        return new FluentOptionalWorker(new FirstFluentMessage(), 'foo bar');
    }

    private function firstStream($return, int $times = 1): Stream
    {
        /** @var Stream|MockObject $stream */
        $stream = $this->createMock(Stream::class);
        $stream->expects($this->exactly($times))->method('first')->willReturn($return);
        $stream->expects($this->never())->method($this->logicalNot($this->matches('first')));
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

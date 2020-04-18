<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Match\nth;

use InvalidArgumentException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Exception\NoSuchElementFluentException;
use TRegx\CleanRegex\Internal\Exception\Messages\FirstFluentMessage;
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
        $pattern = new FluentMatchPattern($this->stream(['a' => 'foo', 'b' => 'bar', 6 => 'lorem', 7 => 'ipsum']), $this->worker());

        // when
        $result = $pattern->nth(0);

        // then
        $this->assertEquals('foo', $result);
    }

    /**
     * @test
     */
    public function shouldReturnNull()
    {
        // given
        $pattern = new FluentMatchPattern($this->stream([null]), $this->worker());

        // when
        $result = $pattern->nth(0);

        // then
        $this->assertNull($result);
    }

    /**
     * @test
     */
    public function shouldGetThird()
    {
        // given
        $pattern = new FluentMatchPattern($this->stream(['a' => 'foo', 'b' => 'bar', 6 => 'lorem', 7 => 'ipsum']), $this->worker());

        // when
        $result = $pattern->nth(2);

        // then
        $this->assertEquals('lorem', $result);
    }

    /**
     * @test
     */
    public function shouldThrow_onNegativeIndex()
    {
        // given
        $pattern = new FluentMatchPattern($this->zeroInteraction(), $this->worker());

        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Negative index: -2');

        // when
        $pattern->nth(-2);
    }

    /**
     * @test
     */
    public function shouldThrow_onTooHigher()
    {
        // given
        $pattern = new FluentMatchPattern($this->stream(['foo']), $this->worker());

        // then
        $this->expectException(NoSuchElementFluentException::class);
        $this->expectExceptionMessage('Expected to get the #2 element from fluent pattern, but the elements feed has 1 elements.');

        // when
        $pattern->nth(2);
    }

    private function worker(): FluentOptionalWorker
    {
        return new FluentOptionalWorker(new FirstFluentMessage(), 'foo bar');
    }

    private function stream(array $return, int $times = 1): Stream
    {
        /** @var Stream|MockObject $stream */
        $stream = $this->createMock(Stream::class);
        $stream->expects($this->exactly($times))->method('all')->willReturn($return);
        $stream->expects($this->never())->method($this->logicalNot($this->matches('all')));
        return $stream;
    }

    private function zeroInteraction(): Stream
    {
        /** @var Stream|MockObject $stream */
        $stream = $this->createMock(Stream::class);
        $stream->expects($this->never())->method($this->anything());
        return $stream;
    }
}

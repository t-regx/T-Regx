<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Match\nth;

use InvalidArgumentException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Exception\NoSuchElementFluentException;
use TRegx\CleanRegex\Internal\Factory\Worker\FluentStreamWorker;
use TRegx\CleanRegex\Internal\Match\Stream\Upstream;
use TRegx\CleanRegex\Match\FluentMatchPattern;

/**
 * @covers \TRegx\CleanRegex\Match\FluentMatchPattern::nth
 */
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
        $this->assertSame('foo', $result);
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
        $this->assertSame('lorem', $result);
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
        $this->expectExceptionMessage('Expected to get the 2-nth element from fluent pattern, but the elements feed has 1 element(s)');

        // when
        $pattern->nth(2);
    }

    private function worker(): FluentStreamWorker
    {
        return new FluentStreamWorker();
    }

    private function stream(array $return, int $times = 1): Upstream
    {
        /** @var Upstream|MockObject $stream */
        $stream = $this->createMock(Upstream::class);
        $stream->expects($this->exactly($times))->method('all')->willReturn($return);
        $stream->expects($this->never())->method($this->logicalNot($this->matches('all')));
        return $stream;
    }

    private function zeroInteraction(): Upstream
    {
        /** @var Upstream|MockObject $stream */
        $stream = $this->createMock(Upstream::class);
        $stream->expects($this->never())->method($this->anything());
        return $stream;
    }
}

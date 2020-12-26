<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Match\findNth;

use InvalidArgumentException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Test\Utils\CustomException;
use Test\Utils\Functions;
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
    public function shouldFindSecond()
    {
        // given
        $pattern = new FluentMatchPattern($this->stream(['a' => 'foo', 'b' => 'bar'], 3), $this->worker());

        // when + then
        $this->assertSame('bar', $pattern->findNth(1)->orReturn('missing'));
        $this->assertSame('bar', $pattern->findNth(1)->orElse('strtolower'));
        $this->assertSame('bar', $pattern->findNth(1)->orThrow());
    }

    /**
     * @test
     */
    public function shouldFindFirst_throwEmpty()
    {
        // given
        $pattern = new FluentMatchPattern($this->stream([]), $this->worker());

        // then
        $this->expectException(NoSuchElementFluentException::class);
        $this->expectExceptionMessage("Expected to get the 0-th element from fluent pattern, but the elements feed has 0 element(s).");

        // when
        $pattern->findNth(0)->orThrow();
    }

    /**
     * @test
     */
    public function shouldFindFirst_orReturn()
    {
        // given
        $pattern = new FluentMatchPattern($this->stream([]), $this->worker());

        // when
        $result = $pattern->findNth(0)->orReturn('otherValue');

        // then
        $this->assertSame('otherValue', $result);
    }

    /**
     * @test
     */
    public function shouldFindFirst_orElse()
    {
        // given
        $pattern = new FluentMatchPattern($this->stream([]), $this->worker());

        // when
        $result = $pattern->findNth(0)->orElse(Functions::constant('otherValue'));

        // then
        $this->assertSame('otherValue', $result);
    }

    /**
     * @test
     */
    public function shouldFindFirst_orElse_notPassArguments()
    {
        // given
        $pattern = new FluentMatchPattern($this->stream([]), $this->worker());

        // when
        $pattern->findNth(0)->orElse(function () {
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
        $pattern = new FluentMatchPattern($this->stream([]), $this->worker());

        // then
        $this->expectException(CustomException::class);
        $this->expectExceptionMessage("Expected to get the 2-th element from fluent pattern, but the elements feed has 0 element(s).");

        // when
        $pattern->findNth(2)->orThrow(CustomException::class);
    }

    /**
     * @test
     */
    public function shouldReturnNull()
    {
        // given
        $pattern = new FluentMatchPattern($this->stream([null]), $this->worker());

        // when
        $result = $pattern->findNth(0)->orThrow();

        // then
        $this->assertNull($result);
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

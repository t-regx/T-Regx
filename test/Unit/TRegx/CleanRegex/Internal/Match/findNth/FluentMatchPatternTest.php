<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Match\findNth;

use InvalidArgumentException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Test\Utils\CustomException;
use TRegx\CleanRegex\Exception\NoSuchElementFluentException;
use TRegx\CleanRegex\Internal\Exception\Messages\NoFirstElementFluentMessage;
use TRegx\CleanRegex\Internal\Factory\NotMatchedFluentOptionalWorker;
use TRegx\CleanRegex\Internal\Match\Switcher\Stream;
use TRegx\CleanRegex\Match\FluentMatchPattern;

class FluentMatchPatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldFindSecond()
    {
        // given
        $pattern = new FluentMatchPattern($this->switcher(['a' => 'foo', 'b' => 'bar'], 3), $this->worker());

        // when + then
        $this->assertEquals('bar', $pattern->findNth(1)->orReturn('missing'));
        $this->assertEquals('bar', $pattern->findNth(1)->orElse('strtolower'));
        $this->assertEquals('bar', $pattern->findNth(1)->orThrow());
    }

    /**
     * @test
     */
    public function shouldFindFirst_throwEmpty()
    {
        // given
        $pattern = new FluentMatchPattern($this->switcher([]), $this->worker());

        // then
        $this->expectException(NoSuchElementFluentException::class);
        $this->expectExceptionMessage("Expected to get the #0 element from fluent pattern, but the elements feed has 0 elements.");

        // when
        $pattern->findNth(0)->orThrow();
    }

    /**
     * @test
     */
    public function shouldFindFirst_orReturn()
    {
        // given
        $pattern = new FluentMatchPattern($this->switcher([]), $this->worker());

        // when
        $result = $pattern->findNth(0)->orReturn('otherValue');

        // then
        $this->assertEquals('otherValue', $result);
    }

    /**
     * @test
     */
    public function shouldFindFirst_orElse()
    {
        // given
        $pattern = new FluentMatchPattern($this->switcher([]), $this->worker());

        // when
        $result = $pattern->findNth(0)->orElse(function () {
            return 'otherValue';
        });

        // then
        $this->assertEquals('otherValue', $result);
    }

    /**
     * @test
     */
    public function shouldFindFirst_orElse_notPassArguments()
    {
        // given
        $pattern = new FluentMatchPattern($this->switcher([]), $this->worker());

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
        $pattern = new FluentMatchPattern($this->switcher([]), $this->worker());

        // then
        $this->expectException(CustomException::class);
        $this->expectExceptionMessage("Expected to get the #0 element from fluent pattern, but the elements feed has 0 elements.");

        // when
        $pattern->findNth(0)->orThrow(CustomException::class);
    }

    /**
     * @test
     */
    public function shouldReturnNull()
    {
        // given
        $pattern = new FluentMatchPattern($this->switcher([null]), $this->worker());

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

    private function worker(): NotMatchedFluentOptionalWorker
    {
        return new NotMatchedFluentOptionalWorker(new NoFirstElementFluentMessage(), 'foo bar');
    }

    private function switcher(array $return, int $times = 1): Stream
    {
        /** @var Stream|MockObject $switcher */
        $switcher = $this->createMock(Stream::class);
        $switcher->expects($this->exactly($times))->method('all')->willReturn($return);
        $switcher->expects($this->never())->method($this->logicalNot($this->matches('all')));
        return $switcher;
    }

    private function zeroInteraction(): Stream
    {
        /** @var Stream|MockObject $switcher */
        $switcher = $this->createMock(Stream::class);
        $switcher->expects($this->never())->method($this->anything());
        return $switcher;
    }
}

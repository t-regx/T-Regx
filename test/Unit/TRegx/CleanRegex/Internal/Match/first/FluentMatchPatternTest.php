<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Match\first;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Exception\NoSuchElementFluentException;
use TRegx\CleanRegex\Internal\Exception\Messages\NotMatchedMessage;
use TRegx\CleanRegex\Internal\Exception\NoFirstSwitcherException;
use TRegx\CleanRegex\Internal\Factory\NotMatchedFluentOptionalWorker;
use TRegx\CleanRegex\Internal\Match\Switcher\Switcher;
use TRegx\CleanRegex\Match\FluentMatchPattern;

class FluentMatchPatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetFirst()
    {
        // given
        $pattern = new FluentMatchPattern($this->firstSwitcher('foo'), $this->worker(''));

        // when
        $result = $pattern->first();

        // then
        $this->assertEquals('foo', $result);
    }

    /**
     * @test
     */
    public function shouldInvoke_consumer()
    {
        // given
        $pattern = new FluentMatchPattern($this->firstSwitcher('foo'), $this->worker(''));

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
        $pattern->first(function () {
            $this->fail();
        });
    }

    private function worker(string $message): NotMatchedFluentOptionalWorker
    {
        /** @var NotMatchedFluentOptionalWorker|MockObject $mockObject */
        $mockObject = $this->createMock(NotMatchedFluentOptionalWorker::class);
        $mock = $this->createMock(NotMatchedMessage::class);
        $mock->method('getMessage')->willReturn($message);
        $mockObject->method('getMessage')->willReturn($mock);
        return $mockObject;
    }

    private function firstSwitcher($return, int $times = 1): Switcher
    {
        /** @var Switcher|MockObject $switcher */
        $switcher = $this->createMock(Switcher::class);
        $switcher->expects($this->exactly($times))->method('first')->willReturn($return);
        $switcher->expects($this->never())->method($this->logicalNot($this->matches('first')));
        return $switcher;
    }

    private function unmatchedMock(): Switcher
    {
        /** @var Switcher|MockObject $switcher */
        $switcher = $this->createMock(Switcher::class);
        $switcher->expects($this->once())->method('first')->willThrowException(new NoFirstSwitcherException());
        $switcher->expects($this->never())->method($this->logicalNot($this->matches('first')));
        return $switcher;
    }
}

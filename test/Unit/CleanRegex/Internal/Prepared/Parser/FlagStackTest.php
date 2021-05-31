<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Prepared\Parser;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\Flags;
use TRegx\CleanRegex\Internal\Prepared\Parser\FlagStack;

class FlagStackTest extends TestCase
{
    /**
     * @test
     */
    public function shouldHaveGroundState()
    {
        // when
        $stack = new FlagStack(new Flags('bar'));

        // then
        $this->assertHasFlags('bar', $stack);
    }

    /**
     * @test
     */
    public function shouldReadTheStackTip()
    {
        // given
        $stack = new FlagStack(new Flags(''));

        // when
        $stack->put(new Flags('abc'));
        $stack->put(new Flags('def'));
        $stack->put(new Flags('ghi'));

        // then
        $this->assertHasFlags('ghi', $stack);
    }

    /**
     * @test
     */
    public function shouldPutFlagsOnStack()
    {
        // given
        $stack = new FlagStack(new Flags(''));

        // when
        $stack->put(new Flags('abc'));

        // then
        $this->assertHasFlags('abc', $stack);
    }

    /**
     * @test
     */
    public function shouldPop()
    {
        // given
        $stack = new FlagStack(new Flags('bar'));

        // when
        $stack->put(new Flags('abc'));
        $stack->pop();

        // then
        $this->assertHasFlags('bar', $stack);
    }

    /**
     * @test
     */
    public function shouldPopEmpty()
    {
        // given
        $stack = new FlagStack(new Flags('car'));

        // when
        $stack->pop();

        // then
        $this->assertHasFlags('car', $stack);
    }

    /**
     * @test
     */
    public function shouldPopLast()
    {
        // given
        $stack = new FlagStack(new Flags(''));

        // when
        $stack->put(new Flags('abc'));
        $stack->put(new Flags('def'));
        $stack->pop();

        // then
        $this->assertHasFlags('abc', $stack);
    }

    private function assertHasFlags(string $expectedFlags, FlagStack $stack): void
    {
        $this->assertSame($expectedFlags, (string)$stack->peek());
    }
}

<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Prepared\Parser;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\Prepared\Parser\FlagStack;
use TRegx\CleanRegex\Internal\Prepared\Parser\SubpatternFlags;

/**
 * @covers \TRegx\CleanRegex\Internal\Prepared\Parser\FlagStack
 */
class FlagStackTest extends TestCase
{
    /**
     * @test
     */
    public function shouldHaveGroundState()
    {
        // when
        $stack = new FlagStack(new SubpatternFlags('bar'));

        // then
        $this->assertHasFlags('bar', $stack);
    }

    /**
     * @test
     */
    public function shouldReadTheStackTip()
    {
        // given
        $stack = new FlagStack(new SubpatternFlags(''));

        // when
        $stack->put(new SubpatternFlags('abc'));
        $stack->put(new SubpatternFlags('def'));
        $stack->put(new SubpatternFlags('ghi'));

        // then
        $this->assertHasFlags('ghi', $stack);
        $this->assertNotHasFlags('abc', $stack);
        $this->assertNotHasFlags('def', $stack);
    }

    /**
     * @test
     */
    public function shouldPutFlagsOnStack()
    {
        // given
        $stack = new FlagStack(new SubpatternFlags(''));

        // when
        $stack->put(new SubpatternFlags('abc'));

        // then
        $this->assertHasFlags('abc', $stack);
    }

    /**
     * @test
     */
    public function shouldPop()
    {
        // given
        $stack = new FlagStack(new SubpatternFlags('xyz'));

        // when
        $stack->put(new SubpatternFlags('ghi'));
        $stack->pop();

        // then
        $this->assertHasFlags('xyz', $stack);
        $this->assertNotHasFlags('ghi', $stack);
    }

    /**
     * @test
     */
    public function shouldPopEmpty()
    {
        // given
        $stack = new FlagStack(new SubpatternFlags('car'));

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
        $stack = new FlagStack(new SubpatternFlags(''));

        // when
        $stack->put(new SubpatternFlags('abc'));
        $stack->put(new SubpatternFlags('def'));
        $stack->pop();

        // then
        $this->assertHasFlags('abc', $stack);
        $this->assertNotHasFlags('def', $stack);
    }

    private function assertHasFlags(string $expectedFlags, FlagStack $stack): void
    {
        $flags = $stack->peek();
        foreach (\str_split($expectedFlags) as $flag) {
            $this->assertTrue($flags->has($flag));
        }
    }

    private function assertNotHasFlags(string $unwantedFlags, FlagStack $stack): void
    {
        $flags = $stack->peek();
        foreach (\str_split($unwantedFlags) as $flag) {
            $this->assertFalse($flags->has($flag));
        }
    }
}

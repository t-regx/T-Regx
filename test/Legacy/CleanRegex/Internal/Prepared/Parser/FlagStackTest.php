<?php
namespace Test\Legacy\CleanRegex\Internal\Prepared\Parser;

use PHPUnit\Framework\TestCase;
use Test\Utils\Prepared\StandardSubpatternFlags;
use TRegx\CleanRegex\Internal\Prepared\Parser\FlagStack;

/**
 * @deprecated
 * @covers \TRegx\CleanRegex\Internal\Prepared\Parser\FlagStack
 */
class FlagStackTest extends TestCase
{
    use StandardSubpatternFlags;

    /**
     * @test
     */
    public function shouldHaveGroundState()
    {
        $this->assertIsNotExtended(new FlagStack($this->subpatternFlagsStandard()));
    }

    /**
     * @test
     * @depends shouldHaveGroundState
     */
    public function shouldHaveGroundStateExtended()
    {
        $this->assertIsExtended(new FlagStack($this->subpatternFlagsExtended()));
    }

    /**
     * @test
     * @depends shouldHaveGroundState
     */
    public function shouldReadTheStackTip()
    {
        // given
        $stack = new FlagStack($this->subpatternFlagsStandard());
        // when
        $stack->put($this->subpatternFlagsStandard());
        $stack->put($this->subpatternFlagsExtended());
        $stack->put($this->subpatternFlagsStandard());
        // then
        $this->assertIsNotExtended($stack);
    }

    /**
     * @test
     * @depends shouldReadTheStackTip
     */
    public function shouldReadTheStackTipExtended()
    {
        // given
        $stack = new FlagStack($this->subpatternFlagsStandard());
        // when
        $stack->put($this->subpatternFlagsStandard());
        $stack->put($this->subpatternFlagsStandard());
        $stack->put($this->subpatternFlagsExtended());
        // then
        $this->assertIsExtended($stack);
    }

    /**
     * @test
     * @depends shouldReadTheStackTip
     */
    public function shouldPop()
    {
        // given
        $stack = new FlagStack($this->subpatternFlagsStandard());
        // when
        $stack->put($this->subpatternFlagsExtended());
        $stack->pop();
        // then
        $this->assertIsNotExtended($stack);
    }

    /**
     * @test
     * @depends shouldPop
     */
    public function shouldPopExtended()
    {
        // given
        $stack = new FlagStack($this->subpatternFlagsExtended());
        // when
        $stack->put($this->subpatternFlagsStandard());
        $stack->pop();
        // then
        $this->assertIsExtended($stack);
    }

    /**
     * @test
     * @depends shouldPop
     */
    public function shouldPopEmpty()
    {
        // given
        $stack = new FlagStack($this->subpatternFlagsExtended());
        // when
        $stack->pop();
        // then
        $this->assertIsExtended($stack);
    }

    /**
     * @test
     * @depends shouldPop
     */
    public function shouldPopLast()
    {
        // given
        $stack = new FlagStack($this->subpatternFlagsStandard());
        // when
        $stack->put($this->subpatternFlagsExtended());
        $stack->put($this->subpatternFlagsStandard());
        $stack->pop();
        // then
        $this->assertIsExtended($stack);
    }

    /**
     * @test
     * @depends shouldPopLast
     */
    public function shouldPopLastExtended()
    {
        // given
        $stack = new FlagStack($this->subpatternFlagsExtended());
        // when
        $stack->put($this->subpatternFlagsStandard());
        $stack->put($this->subpatternFlagsExtended());
        $stack->pop();
        // then
        $this->assertIsNotExtended($stack);
    }

    private function assertIsNotExtended(FlagStack $stack): void
    {
        $this->assertFalse($stack->peek()->isExtended());
    }

    private function assertIsExtended(FlagStack $stack): void
    {
        $this->assertTrue($stack->peek()->isExtended());
    }
}

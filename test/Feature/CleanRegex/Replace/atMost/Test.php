<?php
namespace Test\Feature\CleanRegex\Replace\atMost;

use PHPUnit\Framework\TestCase;
use Test\Utils\Backtrack\CausesBacktracking;
use Test\Utils\Functions;
use TRegx\CleanRegex\Exception\ReplacementExpectationFailedException;
use TRegx\SafeRegex\Exception\CatastrophicBacktrackingException;

/**
 * @coversNothing
 */
class Test extends TestCase
{
    use CausesBacktracking;

    /**
     * @test
     */
    public function replaceAtMost0_replace0()
    {
        // when
        $replaced = pattern('Foo')->replace('Bar')->atMost(1)->with('Door');
        // then
        $this->assertSame('Bar', $replaced);
    }

    /**
     * @test
     */
    public function replaceAtMost1_replace1()
    {
        // when
        $replaced = pattern('Foo')->replace('Foo')->atMost(1)->with('Bar');
        // then
        $this->assertSame('Bar', $replaced);
    }

    /**
     * @test
     */
    public function replaceAtMost2_shouldReplace1()
    {
        // when
        $replaced = pattern('Foo')->replace('Foo,Cat')->atMost(2)->with('Bar');
        // then
        $this->assertSame('Bar,Cat', $replaced);
    }

    /**
     * @test
     */
    public function replaceAtMost2_replace2()
    {
        // when
        $replaced = pattern('Foo')->replace('Foo, Foo')->atMost(2)->with('Bar');
        // then
        $this->assertSame('Bar, Bar', $replaced);
    }

    /**
     * @test
     */
    public function replaceAtMost3_shouldReplace2()
    {
        // when
        $replaced = pattern('Foo')->replace('Foo,Foo')->atMost(3)->with('Bar');
        // then
        $this->assertSame('Bar,Bar', $replaced);
    }

    /**
     * @test
     */
    public function replaceAtMost3_shouldReplace0()
    {
        // when
        $replaced = pattern('Foo')->replace('Bar,Bar')->atMost(3)->with('Door');
        // then
        $this->assertSame('Bar,Bar', $replaced);
    }

    /**
     * @test
     */
    public function replaceAtMost3_shouldReplace0_callback()
    {
        // when
        $replaced = pattern('Foo')->replace('Bar,Bar')->atMost(3)->callback(Functions::fail());
        // then
        $this->assertSame('Bar,Bar', $replaced);
    }

    /**
     * @test
     */
    public function replaceAtMost1_ignoreUnmatched()
    {
        // when
        $replaced = pattern('Foo')->replace('Bar')->atMost(1)->with('Bar');
        // then
        $this->assertSame('Bar', $replaced);
    }

    /**
     * @test
     */
    public function replaceAtMost2_shouldThrowFor3()
    {
        // then
        $this->expectException(ReplacementExpectationFailedException::class);
        $this->expectExceptionMessage('Expected to perform at most 2 replacement(s), but more than 2 replacement(s) would have been performed');
        // when
        pattern('Foo')->replace('Foo,Foo,Foo')->atMost(2)->with('Bar');
    }

    /**
     * @test
     */
    public function replaceAtMost1_shouldThrowFor2()
    {
        // then
        $this->expectException(ReplacementExpectationFailedException::class);
        $this->expectExceptionMessage('Expected to perform at most 1 replacement(s), but more than 1 replacement(s) would have been performed');
        // when
        pattern('Foo')->replace('Foo,Foo')->atMost(1)->with('Bar');
    }

    /**
     * @test
     */
    public function shouldThrowCatastrophicBacktracking_WhileCheckingLast()
    {
        // then
        $this->expectException(CatastrophicBacktrackingException::class);
        // when
        $this->backtrackingReplace(2)->atMost(2)->with('Bar');
    }

    /**
     * @test
     */
    public function shouldNotThrowCatastrophicBacktracking_WhileCheckingNextToLast()
    {
        // then
        $this->expectException(ReplacementExpectationFailedException::class);
        $this->expectExceptionMessage('Expected to perform at most 1 replacement(s), but more than 1 replacement(s) would have been performed');
        // when
        $this->backtrackingReplace(2)->atMost(1)->with('Bar');
    }
}

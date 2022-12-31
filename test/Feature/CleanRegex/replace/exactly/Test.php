<?php
namespace Test\Feature\CleanRegex\replace\exactly;

use PHPUnit\Framework\TestCase;
use Test\Utils\Backtrack\CausesBacktracking;
use Test\Utils\Functions;
use Test\Utils\TestCase\TestCaseExactMessage;
use TRegx\CleanRegex\Exception\ReplacementExpectationFailedException;
use TRegx\SafeRegex\Exception\CatastrophicBacktrackingException;

/**
 * @coversNothing
 */
class Test extends TestCase
{
    use TestCaseExactMessage, CausesBacktracking;

    /**
     * @test
     */
    public function shouldReplaceExactly1()
    {
        // when
        $replaced = pattern('Foo')->replace('"Foo"')->exactly(1)->with('Bar');
        // then
        $this->assertSame('"Bar"', $replaced);
    }

    /**
     * @test
     */
    public function shouldReplaceExactly2()
    {
        // when
        $replaced = pattern('Foo')->replace('Foo, Foo')->exactly(2)->with('Bar');
        // then
        $this->assertSame('Bar, Bar', $replaced);
    }

    /**
     * @test
     */
    public function shouldReplaceExactly1_Superfluous()
    {
        // then
        $this->expectException(ReplacementExpectationFailedException::class);
        $this->expectExceptionMessage('Expected to perform exactly 1 replacement(s), but more than 1 replacement(s) would have been performed');
        // when
        pattern('Foo')->replace('Foo, Foo')->exactly(1)->with('Dead');
    }

    /**
     * @test
     */
    public function shouldReplaceExactly1_Insufficient()
    {
        // then
        $this->expectException(ReplacementExpectationFailedException::class);
        $this->expectExceptionMessage('Expected to perform exactly 1 replacement(s), but 0 replacement(s) were actually performed');
        // when
        pattern('Foo')->replace('Bar')->exactly(1)->with('Dead');
    }

    /**
     * @test
     */
    public function shouldThrowCatastrophicBacktracking_WhileCheckingLast()
    {
        // then
        $this->expectException(CatastrophicBacktrackingException::class);
        $this->expectExceptionMessage('After invoking preg_replace(), preg_last_error() returned PREG_BACKTRACK_LIMIT_ERROR');
        // when
        $this->backtrackingReplace(2)->exactly(2)->with('Bar');
    }

    /**
     * @test
     */
    public function testExactly1_Insufficient_callback()
    {
        // then
        $this->expectException(ReplacementExpectationFailedException::class);
        $this->expectExceptionMessage('Expected to perform exactly 1 replacement(s), but 0 replacement(s) were actually performed');
        // when
        pattern('Foo')->replace('Bar')->exactly(1)->callback(Functions::fail());
    }

    /**
     * @test
     */
    public function testExactly1_Superfluous_callback()
    {
        // then
        $this->expectException(ReplacementExpectationFailedException::class);
        $this->expectExceptionMessage('Expected to perform exactly 1 replacement(s), but more than 1 replacement(s) would have been performed');
        // when
        pattern('Foo')->replace('Foo, Foo, Foo')->exactly(1)->callback(Functions::constant('Bar'));
    }

    /**
     * @test
     */
    public function shouldThrowCatastrophicBacktracking_WhileCheckingLast_callback()
    {
        // then
        $this->expectException(ReplacementExpectationFailedException::class);
        $this->expectExceptionMessage('Expected to perform exactly 1 replacement(s), but more than 1 replacement(s) would have been performed');
        // when
        $this->backtrackingReplace(2)->exactly(1)->callback(Functions::constant('Foo'));
    }

    /**
     * @test
     */
    public function shouldReplaceExactly1_withGroup()
    {
        // when
        $result = pattern('Foo(\d+)')->replace('"Foo14"')->exactly(1)->withGroup(1);
        // then
        $this->assertSame('"14"', $result);
    }

    /**
     * @test
     */
    public function shouldReplaceExactly1_Superfluous_withGroup()
    {
        // then
        $this->expectException(ReplacementExpectationFailedException::class);
        $this->expectExceptionMessage('Expected to perform exactly 1 replacement(s), but more than 1 replacement(s) would have been performed');
        // when
        pattern('(Foo)')->replace('"Foo", Foo')->exactly(1)->withGroup(1);
    }

    /**
     * @test
     */
    public function exactly2_shouldThrow_Insufficient()
    {
        // then
        $this->expectException(ReplacementExpectationFailedException::class);
        $this->expectExceptionMessage('Expected to perform exactly 2 replacement(s), but 1 replacement(s) were actually performed');
        // when
        pattern('Foo')->replace('Foo Bar Bar Bar')->exactly(2)->with('Bar');
    }

    /**
     * @test
     */
    public function exactly2_shouldThrow_Superfluous()
    {
        // then
        $this->expectException(ReplacementExpectationFailedException::class);
        $this->expectExceptionMessage('Expected to perform exactly 2 replacement(s), but more than 2 replacement(s) would have been performed');
        // when
        pattern('Foo')->replace('Foo Foo Foo Bar')->exactly(2)->with('Bar');
    }
}

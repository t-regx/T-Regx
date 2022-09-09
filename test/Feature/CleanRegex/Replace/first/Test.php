<?php
namespace Test\Feature\CleanRegex\Replace\first;

use PHPUnit\Framework\TestCase;
use Test\Utils\Backtrack\CausesBacktracking;
use Test\Utils\Functions;
use Test\Utils\TestCase\TestCaseExactMessage;
use TRegx\CleanRegex\Exception\ReplacementExpectationFailedException;

/**
 * @coversNothing
 */
class Test extends TestCase
{
    use TestCaseExactMessage, CausesBacktracking;

    /**
     * @test
     */
    public function shouldReplaceFirst()
    {
        // when
        $replaced = pattern('Foo')->replace('"Foo"')->first()->with('Bar');
        // then
        $this->assertSame('"Bar"', $replaced);
    }

    /**
     * @test
     */
    public function shouldReplaceFirst_withReferences()
    {
        // when
        $replaced = pattern('(127)')->replace('127.0.0.1')->first()->withReferences('<$1>');
        // then
        $this->assertSame('<127>.0.0.1', $replaced);
    }

    /**
     * @test
     */
    public function shouldReplaceFirst_callback()
    {
        // when
        $replaced = pattern('127')->replace('127.230.35.10')->first()->callback(Functions::charAt(0));
        // then
        $this->assertSame('1.230.35.10', $replaced);
    }

    /**
     * @test
     */
    public function shouldReplaceFirst_withGroup()
    {
        // when
        $replaced = pattern('!(123)!')->replace('!123! !345!')->first()->withGroup(1);
        // then
        $this->assertSame('123 !345!', $replaced);
    }

    /**
     * @test
     */
    public function shouldReplaceFirst_Superfluous()
    {
        // then
        $this->expectException(ReplacementExpectationFailedException::class);
        $this->expectExceptionMessage('Expected to perform exactly 1 replacement(s), but more than 1 replacement(s) would have been performed');
        // when
        pattern('Foo')->replace('Foo, Foo')->first()->with('Dead');
    }

    /**
     * @test
     */
    public function shouldReplaceFirst_Insufficient()
    {
        // then
        $this->expectException(ReplacementExpectationFailedException::class);
        $this->expectExceptionMessage('Expected to perform exactly 1 replacement(s), but 0 replacement(s) were actually performed');
        // when
        pattern('Foo')->replace('Bar')->first()->with('Dead');
    }

    /**
     * @test
     */
    public function shouldReplaceFirst_Insufficient_callback()
    {
        // then
        $this->expectException(ReplacementExpectationFailedException::class);
        $this->expectExceptionMessage('Expected to perform exactly 1 replacement(s), but 0 replacement(s) were actually performed');
        // when
        pattern('Foo')->replace('Bar')->first()->callback(Functions::fail());
    }

    /**
     * @test
     */
    public function shouldReplaceFirst_Superfluous_callback()
    {
        // then
        $this->expectException(ReplacementExpectationFailedException::class);
        $this->expectExceptionMessage('Expected to perform exactly 1 replacement(s), but more than 1 replacement(s) would have been performed');
        // when
        pattern('Foo')->replace('Foo, Foo, Foo')->first()->callback(Functions::constant('Bar'));
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
        $this->backtrackingReplace(2)->first()->callback(Functions::constant('Foo'));
    }

    /**
     * @test
     */
    public function shouldReplaceExactlyFirst_Superfluous()
    {
        // then
        $this->expectException(ReplacementExpectationFailedException::class);
        $this->expectExceptionMessage('Expected to perform exactly 1 replacement(s), but more than 1 replacement(s) would have been performed');
        // when
        pattern('Foo')->replace('"Foo", Foo')->first()->with('Bar');
    }

    /**
     * @test
     */
    public function shouldReplaceExactlyFirst_withGroup()
    {
        // when
        $result = pattern('Foo(\d+)')->replace('"Foo14"')->first()->withGroup(1);
        // then
        $this->assertSame('"14"', $result);
    }

    /**
     * @test
     */
    public function shouldReplaceExactlyFirst_Superfluous_withGroup()
    {
        // then
        $this->expectException(ReplacementExpectationFailedException::class);
        $this->expectExceptionMessage('Expected to perform exactly 1 replacement(s), but more than 1 replacement(s) would have been performed');
        // when
        pattern('(Foo)')->replace('"Foo", Foo')->first()->withGroup(1);
    }
}

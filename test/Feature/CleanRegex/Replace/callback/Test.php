<?php
namespace Test\Feature\CleanRegex\Replace\callback;

use PHPUnit\Framework\TestCase;
use Test\Utils\Backtrack\CausesBacktracking;
use Test\Utils\Functions;
use Test\Utils\TestCase\TestCasePasses;
use TRegx\CleanRegex\Exception\InvalidReplacementException;
use TRegx\CleanRegex\Exception\ReplacementExpectationFailedException;
use TRegx\CleanRegex\Pattern;
use TRegx\Exception\MalformedPatternException;

/**
 * @coversNothing
 */
class Test extends TestCase
{
    use TestCasePasses, CausesBacktracking;

    /**
     * @test
     */
    public function shouldReplace()
    {
        // given
        $pattern = pattern('\w+');
        // when
        $replaced = $pattern->replace('Joffrey, Cersei, Ilyn Payne, The Hound')->callback(Functions::charAt(0));
        // then
        $this->assertSame('J, C, I P, T H', $replaced);
    }

    /**
     * @test
     */
    public function shouldThrow_forInvalidTypeArray()
    {
        // then
        $this->expectException(InvalidReplacementException::class);
        $this->expectExceptionMessage('Invalid callback() callback return type. Expected string, but array (0) given');
        // when
        pattern('\w+')->replace('Foo')->callback(Functions::constant([]));
    }

    /**
     * @test
     */
    public function shouldThrow_forInvalidTypeInteger()
    {
        // then
        $this->expectException(InvalidReplacementException::class);
        $this->expectExceptionMessage('Invalid callback() callback return type. Expected string, but integer (12) given');
        // when
        pattern('Foo')->replace('Foo')->callback(Functions::constant(12));
    }

    /**
     * @test
     */
    public function shouldThrowMalformedPatternException()
    {
        // when
        $this->expectException(MalformedPatternException::class);
        $this->expectExceptionMessage('Quantifier does not follow a repeatable item at offset 0');
        // when
        pattern('?')->replace('Bar')->callback(Functions::fail());
    }

    /**
     * @test
     */
    public function shouldInvokeUpToLimit()
    {
        // when
        Pattern::of('.')->replace('Lorem')->only(3)->callback(Functions::collect($details, ''));
        // then
        $this->assertSame(3, \count($details));
    }

    /**
     * @test
     */
    public function shouldNotInvokeCallback()
    {
        // given
        Pattern::of('Foo')->replace('Bar')->callback(Functions::fail());
        // then
        $this->pass();
    }

    /**
     * @test
     */
    public function shouldThrowCatastrophicBacktracking_WhileCheckingLast_callback()
    {
        // then
        $this->expectException(ReplacementExpectationFailedException::class);
        $this->expectExceptionMessage('Expected to perform exactly 1 replacement(s), but at least 2 replacement(s) would have been performed');
        // when
        $this->backtrackingReplace(2)->first()->exactly()->callback(Functions::constant('Foo'));
    }
}

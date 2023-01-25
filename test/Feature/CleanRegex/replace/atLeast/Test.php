<?php
namespace Test\Feature\CleanRegex\replace\atLeast;

use PHPUnit\Framework\TestCase;
use Test\Utils\Functions;
use TRegx\CleanRegex\Exception\ReplacementExpectationFailedException;
use TRegx\CleanRegex\Pattern;

/**
 * @coversNothing
 */
class Test extends TestCase
{
    /**
     * @test
     */
    public function replaceAtLeast0_shouldReplace0()
    {
        // when
        $replaced = Pattern::of('Foo')->replace('Bar')->atLeast(0)->with('Door');
        // then
        $this->assertSame('Bar', $replaced);
    }

    /**
     * @test
     */
    public function replaceAtLeast1_shouldReplace1()
    {
        // when
        $replaced = Pattern::of('Foo')->replace('Foo')->atLeast(1)->with('Bar');
        // then
        $this->assertSame('Bar', $replaced);
    }

    /**
     * @test
     */
    public function replaceAtLeast1_shouldReplace3()
    {
        // when
        $replaced = Pattern::of('Foo')->replace('Foo,Foo,Foo')->atLeast(1)->with('Bar');
        // then
        $this->assertSame('Bar,Bar,Bar', $replaced);
    }

    /**
     * @test
     */
    public function replaceAtLeast1_shouldThrowFor0()
    {
        // then
        $this->expectException(ReplacementExpectationFailedException::class);
        $this->expectExceptionMessage('Expected to perform at least 1 replacement(s), but 0 replacement(s) were actually performed');
        // when
        Pattern::of('Foo')->replace('Bar')->atLeast(1)->with('Bar');
    }

    /**
     * @test
     */
    public function replaceAtLeast2_shouldReplace2()
    {
        // when
        $replaced = Pattern::of('Foo')->replace('Foo,Foo')->atLeast(2)->with('Bar');
        // then
        $this->assertSame('Bar,Bar', $replaced);
    }

    /**
     * @test
     */
    public function replaceAtLeast2_shouldReplace3()
    {
        // when
        $replaced = Pattern::of('Foo')->replace('Foo,Foo,Foo')->atLeast(2)->with('Bar');
        // then
        $this->assertSame('Bar,Bar,Bar', $replaced);
    }

    /**
     * @test
     */
    public function replaceAtLeast2_shouldThrowFor1()
    {
        // then
        $this->expectException(ReplacementExpectationFailedException::class);
        $this->expectExceptionMessage('Expected to perform at least 2 replacement(s), but 1 replacement(s) were actually performed');
        // when
        Pattern::of('Foo')->replace('Foo')->atLeast(2)->with('Bar');
    }

    /**
     * @test
     */
    public function replaceAtLeast1_shouldThrowFor0_callback()
    {
        // then
        $this->expectException(ReplacementExpectationFailedException::class);
        $this->expectExceptionMessage('Expected to perform at least 1 replacement(s), but 0 replacement(s) were actually performed');
        // when
        Pattern::of('Foo')->replace('Bar')->atLeast(1)->callback(Functions::fail());
    }

    /**
     * @test
     */
    public function shouldAtLeast2_shouldThrowFor0_callback()
    {
        // then
        $this->expectException(ReplacementExpectationFailedException::class);
        $this->expectExceptionMessage('Expected to perform at least 2 replacement(s), but 0 replacement(s) were actually performed');
        // when
        Pattern::of('Foo')->replace('Bar')->atLeast(2)->callback(Functions::fail());
    }
}

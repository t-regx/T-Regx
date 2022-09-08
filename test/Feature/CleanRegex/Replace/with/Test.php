<?php
namespace Test\Feature\CleanRegex\Replace\with;

use PHPUnit\Framework\TestCase;
use TRegx\Exception\MalformedPatternException;

/**
 * @coversNothing
 */
class Test extends TestCase
{
    /**
     * @test
     */
    public function shouldReplace()
    {
        // when
        $replaced = pattern('\d+')->replace('127.0.0.1')->with('X');
        // then
        $this->assertSame('X.X.X.X', $replaced);
    }

    /**
     * @test
     */
    public function shouldReplace_PcreReferencesDollar_Group1()
    {
        // when
        $replaced = pattern('\d+')->replace('127.0.0.1')->with('$1');
        // then
        $this->assertSame('$1.$1.$1.$1', $replaced);
    }

    /**
     * @test
     */
    public function shouldReplace_PcreReferencesDollar_WholeMatch()
    {
        // when
        $replaced = pattern('\d+')->replace('127.0.0.1')->with('$0');
        // then
        $this->assertSame('$0.$0.$0.$0', $replaced);
    }

    /**
     * @test
     */
    public function shouldReplace_PcreReferencesDollar_CurlyBrace()
    {
        // when
        $replaced = pattern('er|ab|ay')->replace('P. Sherman, 42 Wallaby way, Sydney')->with('<${0}>');
        // then
        $this->assertSame('P. Sh<${0}>man, 42 Wall<${0}>y w<${0}>, Sydney', $replaced);
    }

    /**
     * @test
     */
    public function shouldReplace_PcreReferencesDollar_CurlyBrace_TwoDigits()
    {
        // when
        $replaced = pattern('er|ab|ay')->replace('P. Sherman, 42 Wallaby way, Sydney')->with('<${11}>');
        // then
        $this->assertSame('P. Sh<${11}>man, 42 Wall<${11}>y w<${11}>, Sydney', $replaced);
    }

    /**
     * @test
     */
    public function shouldReplace_PcreReferencesDollar_CurlyBrace_DoubleZero()
    {
        // when
        $replaced = pattern('er|ab|ay')->replace('P. Sherman, 42 Wallaby way, Sydney')->with('<${00}>');
        // then
        $this->assertSame('P. Sh<${00}>man, 42 Wall<${00}>y w<${00}>, Sydney', $replaced);
    }

    /**
     * @test
     */
    public function shouldReplace_PcreReferencesBackslash()
    {
        // when
        $replaced = pattern('\d+')->replace('127.0.0.1')->with('\1');
        // then
        $this->assertSame('\1.\1.\1.\1', $replaced);
    }

    /**
     * @test
     */
    public function shouldReplace_PcreReferencesBackslash_TwoDigits()
    {
        // when
        $replaced = pattern('\d+')->replace('127.0.0.1')->with('\11');
        // then
        $this->assertSame('\11.\11.\11.\11', $replaced);
    }

    /**
     * @test
     */
    public function shouldReplace_PcreEscapedBackslash()
    {
        // when
        $replaced = pattern('er|ay|ey')->replace('P. Sherman, 42 Wallaby way, Sydney')->with('\\\\');
        // then
        $this->assertSame('P. Sh\\\\man, 42 Wallaby w\\\\, Sydn\\\\', $replaced);
    }

    /**
     * @test
     */
    public function shouldThrowForMalformedPattern()
    {
        // then
        $this->expectException(MalformedPatternException::class);
        $this->expectExceptionMessage('Quantifier does not follow a repeatable item at offset 0');
        // when
        pattern('?')->replace('Foo')->limit(0)->with('Bar');
    }
}

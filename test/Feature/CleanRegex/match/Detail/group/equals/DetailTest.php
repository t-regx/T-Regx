<?php
namespace Test\Feature\CleanRegex\match\Detail\group\equals;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Pattern;

class DetailTest extends TestCase
{
    /**
     * @test
     */
    public function shouldEqual()
    {
        // given
        $detail = Pattern::of('Foo(Bar)')->match('FooBar')->first();
        // then
        $this->assertTrue($detail->group(1)->equals('Bar'));
    }

    /**
     * @test
     */
    public function shouldNotEqual()
    {
        // given
        $detail = Pattern::of('Foo(Bar)')->match('FooBar')->first();
        // then
        $this->assertFalse($detail->group(1)->equals('something else'));
    }

    /**
     * @test
     */
    public function shouldNotEqual_forUnmatchedGroup()
    {
        // given
        $detail = Pattern::of('Foo(Bar)?')->match('Foo')->first();
        // then
        $this->assertFalse($detail->group(1)->equals('irrelevant'));
    }

    /**
     * @test
     */
    public function shouldNotEqual_forUnmatchedGroup_emptyString()
    {
        // given
        $detail = Pattern::of('Foo(Bar)?')->match('Foo')->first();
        // then
        $this->assertFalse($detail->group(1)->equals(''));
    }

    /**
     * @test
     */
    public function shouldEqual_emptyString()
    {
        // given
        $detail = Pattern::of('Foo()')->match('Foo')->first();
        // then
        $this->assertTrue($detail->group(1)->equals(''));
    }
}

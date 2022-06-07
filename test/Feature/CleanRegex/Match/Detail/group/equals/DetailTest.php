<?php
namespace Test\Feature\CleanRegex\Match\Detail\group\equals;

use PHPUnit\Framework\TestCase;

class DetailTest extends TestCase
{
    /**
     * @test
     */
    public function shouldEqual()
    {
        // given
        $detail = pattern('Foo(Bar)')->match('FooBar')->first();
        // then
        $this->assertTrue($detail->group(1)->equals('Bar'));
    }

    /**
     * @test
     */
    public function shouldNotEqual()
    {
        // given
        $detail = pattern('Foo(Bar)')->match('FooBar')->first();
        // then
        $this->assertFalse($detail->group(1)->equals('something else'));
    }

    /**
     * @test
     */
    public function shouldNotEqual_forUnmatchedGroup()
    {
        // given
        $detail = pattern('Foo(Bar)?')->match('Foo')->first();
        // then
        $this->assertFalse($detail->group(1)->equals('irrelevant'));
    }

    /**
     * @test
     */
    public function shouldNotEqual_forUnmatchedGroup_emptyString()
    {
        // given
        $detail = pattern('Foo(Bar)?')->match('Foo')->first();
        // then
        $this->assertFalse($detail->group(1)->equals(''));
    }

    /**
     * @test
     */
    public function shouldEqual_emptyString()
    {
        // given
        $detail = pattern('Foo()')->match('Foo')->first();
        // then
        $this->assertTrue($detail->group(1)->equals(''));
    }
}

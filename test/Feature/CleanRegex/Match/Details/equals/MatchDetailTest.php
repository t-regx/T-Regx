<?php
namespace Test\Feature\CleanRegex\Match\Details\equals;

use PHPUnit\Framework\TestCase;

class MatchDetailTest extends TestCase
{
    /**
     * @test
     */
    public function shouldEqual_findFirst()
    {
        // given
        $detail = pattern('Foo(Bar)')->match('FooBar')->first();
        // then
        $this->assertTrue($detail->group(1)->equals('Bar'));
    }

    /**
     * @test
     */
    public function shouldNotEqual_findFirst_forUnequal()
    {
        // given
        $detail = pattern('Foo(Bar)')->match('FooBar')->first();
        // then
        $this->assertFalse($detail->group(1)->equals('something else'));
    }

    /**
     * @test
     */
    public function shouldNotEqual_findFirst_forUnmatchedGroup()
    {
        // given
        $detail = pattern('Foo(Bar)?')->match('Foo')->first();
        // then
        $this->assertFalse($detail->group(1)->equals('irrelevant'));
    }
}

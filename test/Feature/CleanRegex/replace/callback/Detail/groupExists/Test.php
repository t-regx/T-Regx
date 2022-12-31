<?php
namespace Test\Feature\CleanRegex\replace\callback\Detail\groupExists;

use PHPUnit\Framework\TestCase;
use Test\Utils\Functions;

class Test extends TestCase
{
    /**
     * @test
     */
    public function shouldHaveWholeGroup()
    {
        // given
        pattern('Foo')->replace('Foo')->callback(Functions::out($detail, ''));
        // when, then
        $this->assertTrue($detail->groupExists(0));
    }

    /**
     * @test
     */
    public function shouldHaveFirstGroup()
    {
        // given
        pattern('(Foo)')->replace('Foo')->callback(Functions::out($detail, ''));
        // when, then
        $this->assertTrue($detail->groupExists(1));
    }

    /**
     * @test
     */
    public function shouldNotHaveFirstGroup()
    {
        // given
        pattern('Foo')->replace('Foo')->callback(Functions::out($detail, ''));
        // when, then
        $this->assertFalse($detail->groupExists(1));
    }

    /**
     * @test
     */
    public function shouldHaveNamedGroup()
    {
        // given
        pattern('(?<group>Foo)')->replace('Foo')->callback(Functions::out($detail, ''));
        // when, then
        $this->assertTrue($detail->groupExists('group'));
    }

    /**
     * @test
     */
    public function shouldNotHaveNamedGroup()
    {
        // given
        pattern('Foo')->replace('Foo')->callback(Functions::out($detail, ''));
        // when, then
        $this->assertFalse($detail->groupExists('group'));
    }

    /**
     * @test
     */
    public function shouldThrowForInvalidGroupName()
    {
        // given
        pattern('Foo')->replace('Foo')->callback(Functions::out($detail, ''));
        // then
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Group name must be an alphanumeric string, not starting with a digit, but '2group' given");
        // when
        $detail->groupExists('2group');
    }

    /**
     * @test
     */
    public function shouldThrowForInvalidGroupIndex()
    {
        // given
        pattern('Foo')->replace('Foo')->callback(Functions::out($detail, ''));
        // then
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Group index must be a non-negative integer, but -1 given');
        // when
        $detail->groupExists(-1);
    }

    /**
     * @test
     */
    public function shouldHaveLastUnmatchedGroup()
    {
        // given
        pattern('(Foo)?')->replace('')->callback(Functions::out($detail, ''));
        // when, then
        $this->assertTrue($detail->groupExists(1));
    }
}

<?php
namespace Test\Feature\CleanRegex\replace\callback\Detail\groupExists;

use PHPUnit\Framework\TestCase;
use Test\Utils\Functions;
use TRegx\CleanRegex\Pattern;

class Test extends TestCase
{
    /**
     * @test
     */
    public function shouldHaveWholeGroup()
    {
        // given
        Pattern::of('Foo')->replace('Foo')->callback(Functions::out($detail, ''));
        // when, then
        $this->assertTrue($detail->groupExists(0));
    }

    /**
     * @test
     */
    public function shouldHaveFirstGroup()
    {
        // given
        Pattern::of('(Foo)')->replace('Foo')->callback(Functions::out($detail, ''));
        // when, then
        $this->assertTrue($detail->groupExists(1));
    }

    /**
     * @test
     */
    public function shouldNotHaveFirstGroup()
    {
        // given
        Pattern::of('Foo')->replace('Foo')->callback(Functions::out($detail, ''));
        // when, then
        $this->assertFalse($detail->groupExists(1));
    }

    /**
     * @test
     */
    public function shouldHaveNamedGroup()
    {
        // given
        Pattern::of('(?<group>Foo)')->replace('Foo')->callback(Functions::out($detail, ''));
        // when, then
        $this->assertTrue($detail->groupExists('group'));
    }

    /**
     * @test
     */
    public function shouldNotHaveNamedGroup()
    {
        // given
        Pattern::of('Foo')->replace('Foo')->callback(Functions::out($detail, ''));
        // when, then
        $this->assertFalse($detail->groupExists('group'));
    }

    /**
     * @test
     */
    public function shouldThrowForInvalidGroupName()
    {
        // given
        Pattern::of('Foo')->replace('Foo')->callback(Functions::out($detail, ''));
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
        Pattern::of('Foo')->replace('Foo')->callback(Functions::out($detail, ''));
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
        Pattern::of('(Foo)?')->replace('')->callback(Functions::out($detail, ''));
        // when, then
        $this->assertTrue($detail->groupExists(1));
    }
}

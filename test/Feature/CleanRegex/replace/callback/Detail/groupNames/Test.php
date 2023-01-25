<?php
namespace Test\Feature\CleanRegex\replace\callback\Detail\groupNames;

use PHPUnit\Framework\TestCase;
use Test\Utils\Functions;
use TRegx\CleanRegex\Pattern;

class Test extends TestCase
{
    /**
     * @test
     */
    public function shouldGetEmptyGroupNames()
    {
        // given
        Pattern::of('Foo')
            ->replace('Foo')
            ->callback(Functions::out($detail, ''));
        // when, then
        $this->assertSame([], $detail->groupNames());
    }

    /**
     * @test
     */
    public function shouldGetGroupNames()
    {
        // given
        Pattern::of('(?<name>Foo)(?<second>Bar)')
            ->replace('FooBar')
            ->callback(Functions::out($detail, ''));
        // when, then
        $this->assertSame(['name', 'second'], $detail->groupNames());
    }

    /**
     * @test
     */
    public function shouldGetGroupNamesLastUnmatched()
    {
        // given
        Pattern::of('(?<name>Foo)(?<second>Bar){0}')
            ->replace('Foo')
            ->callback(Functions::out($detail, ''));
        // when, then
        $this->assertSame(['name', 'second'], $detail->groupNames());
    }

    /**
     * @test
     */
    public function shouldGetGroupNamesLastMatchedEmpty()
    {
        // given
        Pattern::of('(?<name>Foo)(?<second>)Bar')
            ->replace('FooBar')
            ->callback(Functions::out($detail, ''));
        // when, then
        $this->assertSame(['name', 'second'], $detail->groupNames());
    }

    /**
     * @test
     */
    public function shouldGetUnnamedGroups()
    {
        // given
        Pattern::of('(One)(?<two>Two)(Three)(?<four>Four)')
            ->replace('OneTwoThreeFour')
            ->callback(Functions::out($detail, ''));
        // when, then
        $this->assertSame([null, 'two', null, 'four'], $detail->groupNames());
    }
}

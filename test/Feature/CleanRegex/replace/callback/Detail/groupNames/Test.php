<?php
namespace Test\Feature\CleanRegex\replace\callback\Detail\groupNames;

use PHPUnit\Framework\TestCase;
use Test\Utils\Functions;

class Test extends TestCase
{
    /**
     * @test
     */
    public function shouldGetEmptyGroupNames()
    {
        // given
        pattern('Foo')
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
        pattern('(?<name>Foo)(?<second>Bar)')
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
        pattern('(?<name>Foo)(?<second>Bar){0}')
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
        pattern('(?<name>Foo)(?<second>)Bar')
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
        pattern('(One)(?<two>Two)(Three)(?<four>Four)')
            ->replace('OneTwoThreeFour')
            ->callback(Functions::out($detail, ''));
        // when, then
        $this->assertSame([null, 'two', null, 'four'], $detail->groupNames());
    }
}

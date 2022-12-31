<?php
namespace Test\Feature\CleanRegex\match\Detail\groupsCount;

use PHPUnit\Framework\TestCase;
use Test\Utils\Assertion\AssertsGroup;

class DetailTest extends TestCase
{
    use AssertsGroup;

    /**
     * @test
     */
    public function shouldGetGroupsCount()
    {
        // given
        $pattern = pattern('(?<one>first) and (second) and (third), (?:invisible)');
        $detail = $pattern->match("first and second and third, invisible")->first();
        // when, then
        $this->assertSame(3, $detail->groupsCount());
        $this->assertCount(3, $detail->groups());
        $this->assertCount(1, $detail->namedGroups());
    }

    /**
     * @test
     */
    public function shouldGetGroupsCount_twoNamedGroups()
    {
        // given
        $pattern = pattern("(zero), (?<first>first) and (?<second>second), (?:invisible)");
        $detail = $pattern->match('zero, first and second, invisible')->first();
        // when, then
        $this->assertSame(3, $detail->groupsCount());
        $this->assertCount(3, $detail->groups());
        $this->assertCount(2, $detail->namedGroups());
    }
}

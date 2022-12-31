<?php
namespace Test\Feature\CleanRegex\replace\callback\Detail\groupsCount;

use PHPUnit\Framework\TestCase;
use Test\Utils\Functions;

class Test extends TestCase
{
    /**
     * @test
     */
    public function shouldGetGroupsCount()
    {
        // given
        pattern('(?<one>first) and (second)')
            ->replace('first and second')
            ->callback(Functions::out($detail, ''));
        // when
        $groupsCount = $detail->groupsCount();
        // then
        $this->assertSame(2, $groupsCount);
    }

    /**
     * @test
     */
    public function shouldGetGroupsCount_lastEmpty()
    {
        // given
        pattern('(?<one>first) (and) (second)?')
            ->replace('first and ')
            ->callback(Functions::out($detail, ''));
        // when, then
        $this->assertSame(3, $detail->groupsCount());
    }
}

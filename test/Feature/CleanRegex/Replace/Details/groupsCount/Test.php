<?php
namespace Test\Feature\CleanRegex\Replace\Details\groupsCount;

use PHPUnit\Framework\TestCase;
use Test\Utils\DetailFunctions;

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
            ->all()
            ->callback(DetailFunctions::out($detail, ''));
        // when
        $groupsCount = $detail->groupsCount();
        // then
        $this->assertSame(2, $groupsCount);
    }
}

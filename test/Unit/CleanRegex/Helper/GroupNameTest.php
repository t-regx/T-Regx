<?php
namespace Test\Unit\TRegx\CleanRegex\Helper;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Helper\GroupName;

class GroupNameTest extends TestCase
{
    /**
     * @test
     */
    public function test()
    {
        // valid groups
        $this->assertTrue(GroupName::isValid('group'));
        $this->assertTrue(GroupName::isValid('_'));
        $this->assertTrue(GroupName::isValid('_group'));
        $this->assertTrue(GroupName::isValid(0));

        // invalid groups
        $this->assertFalse(GroupName::isValid('2group'));
        $this->assertFalse(GroupName::isValid('group!'));
        $this->assertFalse(GroupName::isValid('0'));
        $this->assertFalse(GroupName::isValid(''));
        $this->assertFalse(GroupName::isValid(-1));

        // assert length
        $this->assertTrue(GroupName::isValid(\str_repeat('a', 32)));
        $this->assertFalse(GroupName::isValid(\str_repeat('a', 33)));
    }
}

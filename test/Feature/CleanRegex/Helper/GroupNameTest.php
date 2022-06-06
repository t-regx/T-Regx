<?php
namespace Test\Feature\CleanRegex\Helper;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Helper\GroupName;

/**
 * @covers \TRegx\CleanRegex\Helper\GroupName
 */
class GroupNameTest extends TestCase
{
    /**
     * @test
     */
    public function shouldValidateGroupFormat()
    {
        $this->assertTrue(GroupName::isValid('group'));
        $this->assertTrue(GroupName::isValid('_'));
        $this->assertTrue(GroupName::isValid('_group'));
        $this->assertFalse(GroupName::isValid('2group'));
        $this->assertFalse(GroupName::isValid('group group'));
        $this->assertFalse(GroupName::isValid('group!'));
        $this->assertFalse(GroupName::isValid('0'));
        $this->assertFalse(GroupName::isValid(''));
    }

    /**
     * @test
     */
    public function shouldValidateGroupValue()
    {
        $this->assertTrue(GroupName::isValid(0));
        $this->assertTrue(GroupName::isValid(1));
        $this->assertTrue(GroupName::isValid(10));
        $this->assertFalse(GroupName::isValid(-1));
        $this->assertFalse(GroupName::isValid(-2));
    }

    /**
     * @test
     */
    public function shouldValidateGroupType()
    {
        $this->assertFalse(GroupName::isValid(null));
        $this->assertFalse(GroupName::isValid(2.25));
    }

    /**
     * @test
     */
    public function shouldValidateGroupNameLength()
    {
        $this->assertTrue(GroupName::isValid(\str_repeat('a', 32)));
        $this->assertFalse(GroupName::isValid(\str_repeat('a', 33)));
    }
}

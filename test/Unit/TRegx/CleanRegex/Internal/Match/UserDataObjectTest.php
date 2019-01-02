<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Match;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\Match\UserDataObject;

class UserDataObjectTest extends TestCase
{
    /**
     * @test
     */
    public function test()
    {
        // given
        $userData = new UserDataObject();

        // when
        $userData->set(14);
        $result = $userData->get();

        // then
        $this->assertEquals(14, $result);
    }
}

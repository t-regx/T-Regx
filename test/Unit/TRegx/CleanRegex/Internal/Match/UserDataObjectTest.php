<?php
namespace TRegx\CleanRegex\Internal\Match;

use PHPUnit\Framework\TestCase;

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

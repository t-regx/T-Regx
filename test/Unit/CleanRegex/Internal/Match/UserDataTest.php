<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Match;

use PHPUnit\Framework\TestCase;
use Test\Fakes\CleanRegex\Match\Details\ByteOffsetDetail;
use TRegx\CleanRegex\Internal\Match\UserData;

/**
 * @covers \TRegx\CleanRegex\Internal\Match\UserData
 */
class UserDataTest extends TestCase
{
    /**
     * @test
     */
    public function test()
    {
        // given
        $container = new UserData();
        $detail = new ByteOffsetDetail(14);
        $otherMatch = new ByteOffsetDetail(15);

        // when
        $container->set($detail, false);
        $container->set($otherMatch, 'value 2');
        $result = $container->get($detail);

        // then
        $this->assertFalse($result);
    }

    /**
     * @test
     */
    public function shouldDefaultWhenMissing()
    {
        // given
        $container = new UserData();

        // when
        $result = $container->get(new ByteOffsetDetail(14));

        // then
        $this->assertNull($result);
    }
}

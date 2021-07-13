<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Match;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\Match\UserData;
use TRegx\CleanRegex\Match\Details\Detail;

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
        $detail = $this->createMockWithByteOffset(14);
        $otherMatch = $this->createMockWithByteOffset(15);

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
        $detail = $this->createMockWithByteOffset(14);

        // when
        $result = $container->get($detail);

        // then
        $this->assertNull($result);
    }

    private function createMockWithByteOffset(int $byteOffset): Detail
    {
        /** @var Detail|MockObject $detail */
        $detail = $this->createMock(Detail::class);
        $detail->method('byteOffset')->willReturn($byteOffset);
        return $detail;
    }
}

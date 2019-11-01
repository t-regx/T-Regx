<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Match;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\Match\UserData;
use TRegx\CleanRegex\Match\Details\Match;

class UserDataTest extends TestCase
{
    /**
     * @test
     */
    public function test()
    {
        // given
        $container = new UserData();
        $match = $this->createMockWithByteOffset(14);
        $otherMatch = $this->createMockWithByteOffset(15);

        // when
        $container->set($match, false);
        $container->set($otherMatch, 'value 2');
        $result = $container->get($match);

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
        $match = $this->createMockWithByteOffset(14);

        // when
        $result = $container->get($match);

        // then
        $this->assertNull($result);
    }

    private function createMockWithByteOffset(int $byteOffset)
    {
        /** @var Match|MockObject $match */
        $match = $this->createMock(Match::class);
        $match->method('byteOffset')->willReturn($byteOffset);
        return $match;
    }
}

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
        $container->get($match)->set('value');
        $container->get($otherMatch)->set('value 2');
        $result = $container->get($match)->get();

        // then
        $this->assertEquals('value', $result);
    }

    private function createMockWithByteOffset(int $byteOffset)
    {
        /** @var Match|MockObject $match */
        $match = $this->createMock(Match::class);
        $match->method('byteOffset')->willReturn($byteOffset);
        return $match;
    }
}

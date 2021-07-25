<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Model;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\Model\Match\RawMatchesOffset;
use TRegx\CleanRegex\Internal\Model\RawMatchesToMatchAdapter;

/**
 * @covers \TRegx\CleanRegex\Internal\Model\RawMatchesToMatchAdapter
 */
class RawMatchesToMatchAdapterTest extends TestCase
{
    /**
     * @dataProvider booleans
     * @param bool $expected
     */
    public function testMatched(bool $expected)
    {
        // given
        $adapter = new RawMatchesToMatchAdapter($this->getRawMatches($expected), 0);

        // when
        $matched = $adapter->matched();

        // then
        $this->assertSame($expected, $matched);
    }

    public function booleans(): array
    {
        return [
            [true],
            [false]
        ];
    }

    private function getRawMatches(bool $expected): RawMatchesOffset
    {
        /** @var RawMatchesOffset|MockObject $rawMatches */
        $rawMatches = $this->createMock(RawMatchesOffset::class);
        $rawMatches->method('matched')->willReturn($expected);
        return $rawMatches;
    }
}

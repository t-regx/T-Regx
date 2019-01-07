<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Model\Adapter;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\Model\Adapter\RawMatchesToMatchAdapter;
use TRegx\CleanRegex\Internal\Model\Matches\IRawMatchesOffset;

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
        $this->assertEquals($expected, $matched);
    }

    public function booleans(): array
    {
        return [
            [true],
            [false]
        ];
    }

    private function getRawMatches(bool $expected): IRawMatchesOffset
    {
        /** @var IRawMatchesOffset|MockObject $rawMatches */
        $rawMatches = $this->createMock(IRawMatchesOffset::class);
        $rawMatches->method('matched')->willReturn($expected);
        return $rawMatches;
    }
}

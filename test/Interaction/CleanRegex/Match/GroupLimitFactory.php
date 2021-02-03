<?php
namespace Test\Interaction\TRegx\CleanRegex\Match;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\Match\Base\Base;
use TRegx\CleanRegex\Internal\Model\Match\RawMatchOffset;
use TRegx\CleanRegex\Internal\Model\Matches\RawMatchesOffset;
use TRegx\CleanRegex\Match\GroupLimit;
use TRegx\CleanRegex\Match\OffsetLimit;

class GroupLimitFactory extends TestCase // this is a dirty hack, only to use protected `createMock()` method
{
    public static function groupLimitAll(TestCase $test, array $allValues, $nameOrIndex = 0): GroupLimit
    {
        /** @var Base|MockObject $base */
        $base = $test->createMock(Base::class);
        $base->expects($test->never())->method('matchOffset');
        $base->method('matchAllOffsets')->willReturn(new RawMatchesOffset([0 => $allValues, $nameOrIndex => $allValues, 1 => $allValues]));

        return new GroupLimit($base, $nameOrIndex, new OffsetLimit($base, $nameOrIndex, false));
    }

    public static function groupLimitFirst(TestCase $test, string $firstValue = ''): GroupLimit
    {
        /** @var Base|MockObject $base */
        $base = $test->createMock(Base::class);
        $base->method('matchOffset')->willReturn(new RawMatchOffset([0 => [$firstValue, 0]], 0));
        $base->expects($test->never())->method('matchAllOffsets');

        return new GroupLimit($base, 0, new OffsetLimit($base, 0, false));
    }

    public static function groupLimitFirstUnmatched(TestCase $test): GroupLimit
    {
        /** @var Base|MockObject $base */
        $base = $test->createMock(Base::class);
        $base->method('matchOffset')->willReturn(new RawMatchOffset([], null));
        $base->expects($test->never())->method('matchAllOffsets');

        return new GroupLimit($base, 0, new OffsetLimit($base, 0, false));
    }
}

<?php
namespace Test\Integration\TRegx\CleanRegex\Match;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\Match\Base\Base;
use TRegx\CleanRegex\Internal\Model\Match\RawMatchOffset;
use TRegx\CleanRegex\Internal\Model\Matches\RawMatchesOffset;
use TRegx\CleanRegex\Match\GroupLimit;
use TRegx\CleanRegex\Match\Offset\MatchOffsetLimit;

class GroupLimitMocks extends TestCase // this is a dirty hack, only to use protected `createMock()` method
{
    public static function createGroupLimit(TestCase $test, array $allValues, string $firstValue = ''): GroupLimit
    {
        /** @var Base|MockObject $base */
        $base = $test->createMock(Base::class);
        $base->method('matchOffset')->willReturn(new RawMatchOffset([0 => [$firstValue, 0]]));
        $base->method('matchAllOffsets')->willReturn(new RawMatchesOffset([0 => $allValues]));

        return new GroupLimit($base, 0, new MatchOffsetLimit($base, 0, false));
    }
}

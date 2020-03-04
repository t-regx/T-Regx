<?php
namespace Test\Integration\TRegx\CleanRegex\Match;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\Match\Base\Base;
use TRegx\CleanRegex\Internal\Model\Match\RawMatchOffset;
use TRegx\CleanRegex\Internal\Model\Matches\RawMatchesOffset;
use TRegx\CleanRegex\Internal\OffsetLimit\MatchOffsetLimitFactory;
use TRegx\CleanRegex\Match\GroupLimit;

class GroupLimitMocks extends TestCase // this is a dirty hack, only to use protected `createMock()` method
{
    public static function mockGroupLimit(TestCase $test, array $allValues, string $firstValue = ''): GroupLimit
    {
        /** @var Base|MockObject $base */
        $base = $test->createMock(Base::class);
        $base->method('matchOffset')->willReturn(new RawMatchOffset([0 => [$firstValue, 0]]));
        $base->method('matchAllOffsets')->willReturn(new RawMatchesOffset([0 => $allValues]));

        return new GroupLimit(new MatchOffsetLimitFactory($base, 0, false), $base, 0);
    }
}

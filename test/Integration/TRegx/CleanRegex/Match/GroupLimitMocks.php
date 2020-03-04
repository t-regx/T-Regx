<?php
namespace Test\Integration\TRegx\CleanRegex\Match;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\GroupLimit\GroupLimitAll;
use TRegx\CleanRegex\Internal\GroupLimit\GroupLimitFirst;
use TRegx\CleanRegex\Internal\InternalPattern;
use TRegx\CleanRegex\Internal\Match\Base\ApiBase;
use TRegx\CleanRegex\Internal\Match\UserData;
use TRegx\CleanRegex\Internal\Model\Match\RawMatchOffset;
use TRegx\CleanRegex\Internal\Model\Matches\RawMatchesOffset;
use TRegx\CleanRegex\Internal\OffsetLimit\MatchOffsetLimitFactory;
use TRegx\CleanRegex\Match\GroupLimit;

class GroupLimitMocks extends TestCase // this is a dirty hack, only to use protected `createMock()` method
{
    public static function mockGroupLimit(TestCase $test, array $allValues, string $firstValue = ''): GroupLimit
    {
        $base = new ApiBase(InternalPattern::pcre('//'), 'unused', new UserData());
        return new GroupLimit(
            self::all($test, $allValues),
            self::first($test, $firstValue),
            new MatchOffsetLimitFactory($base, 0, false), $base, 0);
    }

    private static function first(TestCase $test, string $firstValue): GroupLimitFirst
    {
        /** @var GroupLimitFirst|MockObject $first */
        $first = $test->createMock(GroupLimitFirst::class);
        $first->method('getFirstForGroup')->willReturn(new RawMatchOffset([0 => [$firstValue, 0]]));
        return $first;
    }

    private static function all(TestCase $test, array $allValues): GroupLimitAll
    {
        /** @var GroupLimitAll|MockObject $all */
        $all = $test->createMock(GroupLimitAll::class);
        $all->method('getAllForGroup')->willReturn(new RawMatchesOffset([0 => $allValues]));
        return $all;
    }
}

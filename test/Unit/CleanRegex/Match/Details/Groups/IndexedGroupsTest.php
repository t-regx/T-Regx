<?php
namespace Test\Unit\TRegx\CleanRegex\Match\Details\Groups;

use PHPUnit\Framework\TestCase;
use Test\Fakes\CleanRegex\Internal\Model\GroupKeys;
use Test\Fakes\CleanRegex\Internal\Model\Match\ThrowEntries;
use Test\Fakes\CleanRegex\Internal\ThrowSubject;
use TRegx\CleanRegex\Exception\InternalCleanRegexException;
use TRegx\CleanRegex\Match\Details\Groups\IndexedGroups;

/**
 * These tests are very coupled with each other, with their implementations and
 * with their group-counter part.
 *
 * Test cases below:
 *  - shouldGetGroupsNames()
 *  - shouldGetGroupsCount()
 *
 * ...should be copy-pastes of one another, with the exception of
 * {@see IndexedGroups::names} and {@see IndexedGroups::count} assertions.
 *
 * NamedGroupsTest and IndexedGroupsTest should have exactly alike structure
 * (because they test API that should be similar) and when one changes, so should
 * the other.
 *
 * @covers \TRegx\CleanRegex\Match\Details\Groups\IndexedGroups
 */
class IndexedGroupsTest extends TestCase
{
    /**
     * @test
     * @dataProvider \Test\DataProviders::namedAndIndexedGroups_mixed_keys()
     * @param array $groups
     * @param array $expectedNames
     */
    public function shouldGetGroupsNames(array $groups, array $expectedNames)
    {
        // given
        $matchGroups = new IndexedGroups(new GroupKeys($groups), new ThrowEntries(), new ThrowSubject());

        // when
        $names = $matchGroups->names();

        // then
        $this->assertSame($expectedNames, $names);
    }

    /**
     * @test
     * @dataProvider \Test\DataProviders::namedAndIndexedGroups_mixed_keys()
     * @param array $groups
     * @param array $expectedNames
     */
    public function shouldGetGroupsCount(array $groups, array $expectedNames)
    {
        // given
        $matchGroups = new IndexedGroups(new GroupKeys($groups), new ThrowEntries(), new ThrowSubject());

        // when
        $count = $matchGroups->count();

        // then
        $this->assertCount($count, $expectedNames);
    }

    /**
     * @test
     */
    public function shouldGetGroupsCountInvalid()
    {
        // given
        $matchGroups = new IndexedGroups(new GroupKeys([]), new ThrowEntries(), new ThrowSubject());

        // then
        $this->expectException(InternalCleanRegexException::class);

        // when
        $matchGroups->count();
    }
}

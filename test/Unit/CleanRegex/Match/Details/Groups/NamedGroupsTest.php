<?php
namespace Test\Unit\TRegx\CleanRegex\Match\Details\Groups;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Test\Utils\Impl\ThrowSubject;
use TRegx\CleanRegex\Internal\Model\Match\IRawMatchOffset;
use TRegx\CleanRegex\Match\Details\Groups\NamedGroups;

/**
 * These tests are very coupled with each other, with their implementations and
 * with their group-counter part.
 *
 * Test cases below:
 *  - shouldGetGroupsNames()
 *  - shouldGetGroupsCount()
 *
 * ...should be copy-pastes of one another, with the exception of
 * {@see NamedGroups::names} and {@see NamedGroups::count} assertions.
 *
 * NamedGroupsTest and IndexedGroupsTest should have exactly alike structure
 * (because they test API that should be similar) and when one changes, so should
 * the other.
 */
class NamedGroupsTest extends TestCase
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
        $matchGroups = new NamedGroups($this->match($groups), new ThrowSubject());

        // when
        $names = $matchGroups->names();

        // then
        $this->assertSame(array_values(array_filter($expectedNames, 'is_string')), $names);
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
        $matchGroups = new NamedGroups($this->match($groups), new ThrowSubject());

        // when
        $count = $matchGroups->count();

        // then
        $this->assertCount($count, array_values(array_filter($expectedNames, 'is_string')));
    }

    private function match(array $keys): IRawMatchOffset
    {
        /** @var IRawMatchOffset|MockObject $mock */
        $mock = $this->createMock(IRawMatchOffset::class);
        /**
         * This test knows the implementation details of IndexedGroups, so it knows
         * only to mock {@see IRawMatchOffset::getGroupKeys} method, to remain a unit test.
         * We could de-couple it from the implementation and create a real RawMatchOffset,
         * that doesn't break a contract, but then we'd get an integration test, not a unit test.
         */
        $mock->method('getGroupKeys')->willReturn($keys);
        return $mock;
    }
}

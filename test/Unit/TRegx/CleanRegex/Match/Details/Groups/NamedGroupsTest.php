<?php
namespace Test\Unit\TRegx\CleanRegex\Match\Details\Groups;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\Model\Match\IRawMatchOffset;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\CleanRegex\Match\Details\Groups\NamedGroups;

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
        $matchGroups = new NamedGroups($this->match($groups), new Subject(''));

        // when
        $names = $matchGroups->names();

        // then
        $this->assertEquals(array_values(array_filter($expectedNames, 'is_string')), $names);
    }

    private function match(array $keys): IRawMatchOffset
    {
        /** @var IRawMatchOffset|MockObject $mock */
        $mock = $this->createMock(IRawMatchOffset::class);
        /**
         * This test knows the implementation details of IndexedGroups, so it knows
         * only to mock `getGroupKeys()` method, to remain a unit test.
         * We could de-couple it from the implementation and create a real RawMatchOffset,
         * that doesn't break a contract, but then we'd get an integration test, not a unit test.
         */
        $mock->method('getGroupKeys')->willReturn($keys);
        return $mock;
    }
}

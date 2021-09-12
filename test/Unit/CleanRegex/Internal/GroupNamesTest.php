<?php
namespace Test\Unit\TRegx\CleanRegex\Internal;

use PHPUnit\Framework\TestCase;
use Test\Fakes\CleanRegex\Internal\Model\GroupKeys;
use TRegx\CleanRegex\Internal\GroupNames;

/**
 * @covers \TRegx\CleanRegex\Internal\GroupNames
 */
class GroupNamesTest extends TestCase
{
    /**
     * @test
     * @param array $groupKeys
     * @param array $expected
     * @dataProvider groupKeysAndGroupNames
     */
    public function shouldGetGroupNames(array $groupKeys, array $expected)
    {
        // given
        $groupNames = new GroupNames(new GroupKeys($groupKeys));

        // when
        $names = $groupNames->groupNames();

        // then
        $this->assertSame($expected, $names);
    }

    function groupKeysAndGroupNames(): array
    {
        return [
            [
                [0],
                [],
            ],
            [
                [0, 1, 2, 3],
                [null, null, null]
            ],
            [
                [0, 'a', 1, 'b', 2, 'c', 3],
                ['a', 'b', 'c']
            ],
            [
                [0, 'a', 1, 2, 'c', 3, 'd', 4, 5],
                ['a', null, 'c', 'd', null]
            ],
            [
                [0, 1, 'b', 2, 3, 'd', 4],
                [null, 'b', null, 'd']
            ],
        ];
    }
}

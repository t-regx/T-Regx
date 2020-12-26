<?php
namespace Test\Unit\TRegx\CleanRegex\Internal;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\GroupNames;
use TRegx\CleanRegex\Internal\Model\Match\RawMatchOffset;

class GroupNamesTest extends TestCase
{
    /**
     * @test
     * @param array $input
     * @param array $expected
     * @dataProvider inputArrays
     */
    public function shouldGetGroupNames(array $input, array $expected)
    {
        // given
        $groupNames = new GroupNames(new RawMatchOffset($input));

        // when
        $names = $groupNames->groupNames();

        // then
        $this->assertSame($expected, $names);
    }

    function inputArrays(): array
    {
        return [
            [
                [0 => []],
                [],
            ],
            [
                [0 => [], 1 => [], 2 => [], 3 => []],
                [null, null, null]
            ],
            [
                [0 => [], 'a' => [], 1 => [], 'b' => [], 2 => [], 'c' => [], 3 => []],
                ['a', 'b', 'c']
            ],
            [
                [0 => [], 'a' => [], 1 => [], 2 => [], 'c' => [], 3 => [], 'd' => [], 4 => [], 5 => []],
                ['a', null, 'c', 'd', null]
            ],
            [
                [0 => [], 1 => [], 'b' => [], 2 => [], 3 => [], 'd' => [], 4 => []],
                [null, 'b', null, 'd']
            ],
        ];
    }
}

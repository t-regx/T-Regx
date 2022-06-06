<?php
namespace Test\Unit\CleanRegex\Internal;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\GroupKey\GroupIndex;

/**
 * @covers \TRegx\CleanRegex\Internal\GroupKey\GroupIndex
 */
class GroupIndexTest extends TestCase
{
    /**
     * @test
     * @dataProvider validGroups
     * @param string|int $index
     */
    public function shouldGetNameOrIndex(int $index)
    {
        // given
        $group = new GroupIndex($index);

        // when
        $actual = $group->nameOrIndex();

        // then
        $this->assertSame($index, $actual);
    }

    /**
     * @test
     * @dataProvider validGroups
     * @param string|int $index
     */
    public function shouldCastToString(int $index)
    {
        // given
        $group = new GroupIndex($index);

        // when
        $actual = $group->nameOrIndex();

        // then
        $this->assertSame("#$index", "$group");
    }

    public function validGroups(): array
    {
        return [[0], [14]];
    }

    /**
     * @test
     */
    public function shouldThrowForNegativeInteger()
    {
        // then
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Group index must be a non-negative integer, but -15 given');

        // when
        new GroupIndex(-15);
    }
}

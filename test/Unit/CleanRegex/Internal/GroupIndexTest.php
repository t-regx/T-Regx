<?php
namespace Test\Unit\TRegx\CleanRegex\Internal;

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
    public function shouldGetValidInteger(int $index)
    {
        // given
        $group = new GroupIndex($index);

        // when
        $actual = $group->nameOrIndex();

        // then
        $this->assertSame($index, $actual);
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
        // given
        $group = new GroupIndex(-15);

        // then
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Group index must be a non-negative integer, but -15 given');

        // when
        $group->nameOrIndex();
    }
}

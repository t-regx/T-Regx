<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\GroupKey;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\GroupKey\GroupKey;

/**
 * @covers \TRegx\CleanRegex\Internal\GroupKey\GroupKey
 */
class GroupKeyTest extends TestCase
{
    /**
     * @test
     * @dataProvider validGroups
     * @param string|int $identifier
     */
    public function shouldValidate($identifier)
    {
        // when
        $actual = GroupKey::of($identifier)->nameOrIndex();

        // then
        $this->assertSame($actual, $identifier);
    }

    public function validGroups(): array
    {
        return [
            ['group'],
            ['_group'],
            ['GROUP'],
            ['g'],
            ['a123_'],
            [0],
            [14],
        ];
    }

    /**
     * @test
     * @dataProvider invalidGroup
     * @param string|int $invalidGroupIdentifier
     * @param string $message
     */
    public function shouldThrowForOtherTypes($invalidGroupIdentifier, string $message)
    {
        // then
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage($message);

        // when
        GroupKey::of($invalidGroupIdentifier);
    }

    public function invalidGroup(): array
    {
        return [
            [2.23, 'Group index must be an integer or a string, but double (2.23) given'],
            [null, 'Group index must be an integer or a string, but null given'],
        ];
    }
}

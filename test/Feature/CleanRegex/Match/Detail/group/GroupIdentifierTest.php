<?php
namespace Test\Feature\CleanRegex\Match\Detail\group;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Pattern;

/**
 * @covers \TRegx\CleanRegex\Internal\GroupKey\GroupKey
 */
class GroupIdentifierTest extends TestCase
{
    /**
     * @test
     * @dataProvider validGroups
     * @param string|int $groupIdentifier
     */
    public function shouldBeValidGroup(string $pattern, $groupIdentifier)
    {
        // given
        $detail = Pattern::of($pattern)->match('Foo')->first();
        // when
        $identifier = $detail->group($groupIdentifier)->usedIdentifier();
        // then
        $this->assertSame($identifier, $groupIdentifier);
    }

    public function validGroups(): array
    {
        return [
            ['(?<_group>)', '_group'],
            ['(?<GROUP>)', 'GROUP'],
            ['(?<g>)', 'g'],
            ['(?<a123_>)', 'a123_'],
            ['', 0],
            ['()', 1],
            [str_repeat('()', 14), 14],
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
        // given
        $detail = Pattern::of('Foo')->match('Foo')->first();
        // when
        $detail->group($invalidGroupIdentifier);
    }

    public function invalidGroup(): array
    {
        return [
            [2.23, 'Group index must be an integer or a string, but double (2.23) given'],
            [null, 'Group index must be an integer or a string, but null given'],
        ];
    }
}

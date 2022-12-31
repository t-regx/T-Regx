<?php
namespace Test\Legacy\CleanRegex\Internal\Model\Match;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\Definition;
use TRegx\CleanRegex\Internal\GroupKey\GroupName;
use TRegx\CleanRegex\Internal\Model\LightweightGroupAware;

/**
 * @covers \TRegx\CleanRegex\Internal\Model\LightweightGroupAware
 */
class LightweightGroupAwareTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetGroupKeys()
    {
        // given
        $groupAware = new LightweightGroupAware(new Definition('/(?<group>)/'));

        // when
        $keys = $groupAware->getGroupKeys();

        // then
        $this->assertSame([0, 'group', 1], $keys);
    }

    /**
     * @test
     * @dataProvider existingGroupsAndVerifiers
     * @param string $pattern
     * @param bool $expected
     */
    public function shouldVerify(string $pattern, bool $expected)
    {
        // given
        $groupAware = new LightweightGroupAware(new Definition($pattern));

        // when
        $hasGroup = $groupAware->hasGroup(new GroupName('group'));

        // then
        $this->assertSame($expected, $hasGroup);
    }

    public function existingGroupsAndVerifiers(): array
    {
        return [
            ['/ab (?<group>c)/', true],
            ['/ab (?P<group>c)/', true],
            ["/ab (?'group'c)/", true],
            ["/ab (?'group'c (?<P>xd))/", true],

            ['/ab \(?<group>c\)/', false],
            ['/ab \(?P<group>c\)/', false],
            ["/ab \(?'group'c\)/", false],
        ];
    }
}

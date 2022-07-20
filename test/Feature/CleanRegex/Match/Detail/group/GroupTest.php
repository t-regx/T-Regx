<?php
namespace Test\Feature\CleanRegex\Match\Detail\group;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\Match\Details\Group\MatchedGroup;
use TRegx\CleanRegex\Internal\Match\Details\Group\NotMatchedGroup;
use TRegx\CleanRegex\Match\Details\Group\Group;
use TRegx\CleanRegex\Pattern;

class GroupTest extends TestCase
{
    /**
     * @test
     * @dataProvider groups
     */
    public function shouldBeMatched(Group $group, bool $matched)
    {
        // then
        $this->assertSame($matched, $group->matched());
    }

    /**
     * @test
     * @dataProvider groups
     */
    public function shouldGetIndex(Group $group)
    {
        // when
        $index = $group->index();
        // then
        $this->assertSame(2, $index);
    }

    /**
     * @test
     * @dataProvider groups
     */
    public function shouldGetName(Group $group)
    {
        // when
        $index = $group->name();
        // then
        $this->assertSame('group', $index);
    }

    /**
     * @test
     * @dataProvider groups
     * @param Group $group
     */
    public function shouldGetUsedIdentifier(Group $group)
    {
        // when
        $identifier = $group->usedIdentifier();
        // then
        $this->assertSame(2, $identifier);
    }

    /**
     * @test
     * @dataProvider groups
     * @param Group $group
     * @param bool $matched
     * @param Group $groupByName
     */
    public function shouldGetUsedIdentifier_byName(Group $group, bool $matched, Group $groupByName)
    {
        // when
        $identifier = $groupByName->usedIdentifier();
        // then
        $this->assertSame('group', $identifier);
    }

    /**
     * @test
     * @dataProvider groups
     */
    public function shouldIsIntThrowForInvalidBase(Group $group)
    {
        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid base: -2 (supported bases 2-36, case-insensitive)');
        // when
        $group->isInt(-2);
    }

    /**
     * @test
     * @dataProvider groups
     */
    public function shouldIsIntThrowForInvalidBaseNegative(Group $group)
    {
        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid base: 37 (supported bases 2-36, case-insensitive)');
        // when
        $group->isInt(37);
    }

    /**
     * @test
     * @dataProvider groups
     */
    public function shouldToIntThrowForInvalidBase(Group $group)
    {
        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid base: -2 (supported bases 2-36, case-insensitive)');
        // when
        $group->toInt(-2);
    }

    /**
     * @test
     * @dataProvider groups
     */
    public function shouldToIntThrowForInvalidBaseNegative(Group $group)
    {
        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid base: 37 (supported bases 2-36, case-insensitive)');
        // when
        $group->toInt(37);
    }

    public function groups(): array
    {
        return [
            [$this->matchedGroup(2), true, $this->matchedGroup('group')],
            [$this->unmatchedGroup(2), false, $this->unmatchedGroup('group')],
        ];
    }

    private function matchedGroup($nameOrIndex): \TRegx\CleanRegex\Internal\Match\Details\Group\MatchedGroup
    {
        $detail = Pattern::of('(\d+):(?<group>group)')->match('€, 12:group')->first();
        /**
         * @var \TRegx\CleanRegex\Internal\Match\Details\Group\MatchedGroup $group
         */
        $group = $detail->group($nameOrIndex);
        return $group;
    }

    private function unmatchedGroup($nameOrIndex): NotMatchedGroup
    {
        $detail = Pattern::of('(Foo)(?<group>Bar)?')->match('Foo')->first();
        /**
         * @var NotMatchedGroup $group
         */
        $group = $detail->group($nameOrIndex);
        return $group;
    }
}

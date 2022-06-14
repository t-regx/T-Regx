<?php
namespace Test\Feature\CleanRegex\Match\Details\groups\first;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Test\Utils\Assertion\AssertsGroup;
use Test\Utils\DetailFunctions;

class MatchDetailTest extends TestCase
{
    use AssertsGroup;

    /**
     * @test
     */
    public function shouldGetNamedGroup()
    {
        // given
        pattern('(?<one>first) and (?<two>second)')
            ->match('first and second')
            ->first(DetailFunctions::out($detail));
        // when
        $groups = $detail->namedGroups();
        // then
        $this->assertGroupTexts(['one' => 'first', 'two' => 'second'], $groups);
    }

    /**
     * @test
     */
    public function shouldGetGroupsOffsets_indexedGroups()
    {
        // given
        pattern('(?<one>first ę) and (?<two>second)')
            ->match('first ę and second')
            ->first(DetailFunctions::out($detail));
        // when
        $groups = $detail->groups();
        // then
        $this->assertGroupOffsets([0, 12], $groups);
        $this->assertGroupByteOffsets([0, 13], $groups);
        $this->assertGroupIndicesConsequetive($groups);
    }

    /**
     * @test
     */
    public function shouldGetGroupsOffsets_namedGroups()
    {
        // given
        pattern('(?<one>first ę) and (?<two>second)')
            ->match('first ę and second')
            ->first(DetailFunctions::out($detail));
        // when
        $groups = $detail->namedGroups();
        // then
        $this->assertGroupOffsets(['one' => 0, 'two' => 12], $groups);
        $this->assertGroupByteOffsets(['one' => 0, 'two' => 13], $groups);
        $this->assertGroupIndices(['one' => 1, 'two' => 2], $groups);
    }

    /**
     * @test
     */
    public function shouldGetGroupNames()
    {
        // given
        pattern('(?<one>first) (and) (?<two>second)')
            ->match('first and second')
            ->first(DetailFunctions::out($detail));
        // when, then
        $this->assertSame(['one', null, 'two'], $detail->groupNames());
    }

    /**
     * @test
     */
    public function shouldGetGroupsCount()
    {
        // given
        pattern("(?<one>first) and (second) and (third), (?:don't count me)")
            ->match("first and second and third, don't count me")
            ->first(DetailFunctions::out($detail));
        // when +  then
        $this->assertCount(3, $detail->groups());
        $this->assertCount(1, $detail->namedGroups());
        $this->assertSame(3, $detail->groupsCount());
    }

    /**
     * @test
     */
    public function shouldHaveGroup()
    {
        // given
        pattern('(?<existing>first) and (?<two_existing>second)')
            ->match('first and second')
            ->first(DetailFunctions::out($detail));
        // when, then
        $this->assertTrue($detail->groupExists('existing'));
    }

    /**
     * @test
     */
    public function shouldNotHaveGroup()
    {
        // given
        pattern('(?<one>first) and (?<two>second)')
            ->match('first and second')
            ->first(DetailFunctions::out($detail));
        // when +  then
        $this->assertFalse($detail->groupExists('nonexistent'));
    }

    /**
     * @test
     */
    public function shouldGetGroupsNames()
    {
        // given
        pattern('(zero) (?<existing>first) and (?<two_existing>second)')
            ->match('zero first and second')
            ->first(DetailFunctions::out($detail));
        // when, then
        $this->assertGroupNames([null, 'existing', 'two_existing'], $detail->groups());
        $this->assertGroupNames(['existing' => 'existing', 'two_existing' => 'two_existing'], $detail->namedGroups());
    }

    /**
     * @test
     */
    public function shouldCount()
    {
        // given
        pattern('(Foo)')->match('Foo')->first(DetailFunctions::out($detail));
        // when, then
        $this->assertCount(1, $detail->groups());
        $this->assertCount(0, $detail->namedGroups());
    }

    /**
     * @test
     */
    public function shouldCountTwoGroups()
    {
        // given
        pattern('(zero) (?<first>first) and (?<second>second)')
            ->match('zero first and second')
            ->first(DetailFunctions::out($detail));
        // when, then
        $this->assertCount(3, $detail->groups());
        $this->assertCount(2, $detail->namedGroups());
    }

    /**
     * @test
     */
    public function shouldThrowOnInvalidGroupName()
    {
        // given
        pattern('(?<one>first) and (?<two>second)')
            ->match('first and second')
            ->first(DetailFunctions::out($detail));
        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Group name must be an alphanumeric string, not starting with a digit, but '2sd' given");
        // when
        $detail->groupExists('2sd');
    }
}

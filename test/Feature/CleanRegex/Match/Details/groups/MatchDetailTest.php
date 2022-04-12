<?php
namespace Test\Feature\TRegx\CleanRegex\Match\Details\groups;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Test\Utils\Functions;
use TRegx\CleanRegex\Match\Details\Detail;

class MatchDetailTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetEmptyMatchedGroup()
    {
        // when
        $groups = pattern('([a-z]+)(?:\((\d*)\))?')
            ->match('sin(20) + cos() + tan')
            ->map(function (Detail $detail) {
                return $detail->groups()->texts();
            });
        // then
        $expected = [
            ['sin', '20'], // braces value
            ['cos', ''],   // empty braces
            ['tan', null], // no braces
        ];
        $this->assertSame($expected, $groups);
    }

    /**
     * @test
     */
    public function shouldGetNamedGroup()
    {
        // given
        pattern('(?<one>first) and (?<two>second)')
            ->match('first and second')
            ->first(Functions::out($detail));
        // when
        $groupNames = $detail->namedGroups()->texts();
        // then
        $expected = [
            'one' => 'first',
            'two' => 'second'
        ];
        $this->assertSame($expected, $groupNames);
    }

    /**
     * @test
     */
    public function shouldGetGroupsOffsets_indexedGroups()
    {
        // given
        pattern('(?<one>first ę) and (?<two>second)')
            ->match('first ę and second')
            ->first(Functions::out($detail));
        // when
        $offsets = $detail->groups()->offsets();
        $byteOffsets = $detail->groups()->byteOffsets();
        // then
        $this->assertSame([0, 12], $offsets);
        $this->assertSame([0, 13], $byteOffsets);
    }

    /**
     * @test
     */
    public function shouldGetGroupsOffsets_namedGroups()
    {
        // given
        pattern('(?<one>first ę) and (?<two>second)')
            ->match('first ę and second')
            ->first(Functions::out($detail));
        // when
        $offsets = $detail->namedGroups()->offsets();
        $byteOffsets = $detail->namedGroups()->byteOffsets();
        // then
        $this->assertSame(['one' => 0, 'two' => 12], $offsets);
        $this->assertSame(['one' => 0, 'two' => 13], $byteOffsets);
    }

    /**
     * @test
     */
    public function shouldGetGroupNames()
    {
        // given
        pattern('(?<one>first) (and) (?<two>second)')
            ->match('first and second')
            ->first(Functions::out($detail));
        // when + then
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
            ->first(Functions::out($detail));
        // when +  then
        $this->assertSame(3, $detail->groups()->count());
        $this->assertSame(1, $detail->namedGroups()->count());
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
            ->first(Functions::out($detail));
        // when + then
        $this->assertTrue($detail->hasGroup('existing'));
    }

    /**
     * @test
     */
    public function shouldNotHaveGroup()
    {
        // given
        pattern('(?<one>first) and (?<two>second)')
            ->match('first and second')
            ->first(Functions::out($detail));
        // when +  then
        $this->assertFalse($detail->hasGroup('nonexistent'));
    }

    /**
     * @test
     */
    public function shouldGetGroupsNames()
    {
        // given
        pattern('(zero) (?<existing>first) and (?<two_existing>second)')
            ->match('zero first and second')
            ->first(Functions::out($detail));
        // when + then
        $this->assertSame([null, 'existing', 'two_existing'], $detail->groups()->names());
        $this->assertSame(['existing', 'two_existing'], $detail->namedGroups()->names());
    }

    /**
     * @test
     */
    public function shouldCount()
    {
        // given
        pattern('(Foo)')->match('Foo')->first(Functions::out($detail));
        // when + then
        $this->assertSame(1, $detail->groups()->count());
        $this->assertSame(0, $detail->namedGroups()->count());
    }

    /**
     * @test
     */
    public function shouldCountTwoGroups()
    {
        // given
        pattern('(zero) (?<first>first) and (?<second>second)')
            ->match('zero first and second')
            ->first(Functions::out($detail));
        // when + then
        $this->assertSame(3, $detail->groups()->count());
        $this->assertSame(2, $detail->namedGroups()->count());
    }

    /**
     * @test
     */
    public function shouldThrowOnInvalidGroupName()
    {
        // given
        pattern('(?<one>first) and (?<two>second)')
            ->match('first and second')
            ->first(Functions::out($detail));
        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Group name must be an alphanumeric string, not starting with a digit, but '2sd' given");
        // when
        $detail->hasGroup('2sd');
    }
}

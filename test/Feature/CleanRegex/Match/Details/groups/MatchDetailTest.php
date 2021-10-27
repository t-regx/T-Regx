<?php
namespace Test\Feature\TRegx\CleanRegex\Match\Details\groups;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Match\Details\Detail;

/**
 * @coversNothing
 */
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
            ->first(function (Detail $detail) {
                // when
                $groupNames = $detail->namedGroups()->texts();

                // then
                $expected = [
                    'one' => 'first',
                    'two' => 'second'
                ];
                $this->assertSame($expected, $groupNames);
            });
    }

    /**
     * @test
     */
    public function shouldGetGroupsOffsets_indexedGroups()
    {
        // given
        pattern('(?<one>first ę) and (?<two>second)')
            ->match('first ę and second')
            ->first(function (Detail $detail) {
                // when
                $offsets = $detail->groups()->offsets();
                $byteOffsets = $detail->groups()->byteOffsets();

                // then
                $this->assertSame([0, 12], $offsets);
                $this->assertSame([0, 13], $byteOffsets);
            });
    }

    /**
     * @test
     */
    public function shouldGetGroupsOffsets_namedGroups()
    {
        // given
        pattern('(?<one>first ę) and (?<two>second)')
            ->match('first ę and second')
            ->first(function (Detail $detail) {
                // when
                $offsets = $detail->namedGroups()->offsets();
                $byteOffsets = $detail->namedGroups()->byteOffsets();

                // then
                $this->assertSame(['one' => 0, 'two' => 12], $offsets);
                $this->assertSame(['one' => 0, 'two' => 13], $byteOffsets);
            });
    }

    /**
     * @test
     */
    public function shouldGetGroupNames()
    {
        // given
        pattern('(?<one>first) (and) (?<two>second)')
            ->match('first and second')
            ->first(function (Detail $detail) {
                // when
                $groupNames = $detail->groupNames();

                // then
                $this->assertSame(['one', null, 'two'], $groupNames);
            });
    }

    /**
     * @test
     */
    public function shouldGetGroupsCount()
    {
        // given
        pattern("(?<one>first) and (second) and (third), (?:don't count me)")
            ->match("first and second and third, don't count me")
            ->first(function (Detail $detail) {
                // when
                $groupsCount = $detail->groupsCount();

                // then
                $this->assertSame(3, $groupsCount);
            });
    }

    /**
     * @test
     */
    public function shouldNotHaveGroup()
    {
        // given
        pattern('(?<one>first) and (?<two>second)')
            ->match('first and second')
            ->first(function (Detail $detail) {
                // when
                $has = $detail->hasGroup('nonexistent');

                // then
                $this->assertFalse($has);
            });
    }

    /**
     * @test
     */
    public function shouldHaveGroup()
    {
        // given
        pattern('(?<existing>first) and (?<two_existing>second)')
            ->match('first and second')
            ->first(function (Detail $detail) {
                // when
                $has = $detail->hasGroup('existing');

                // then
                $this->assertTrue($has);
            });
    }

    /**
     * @test
     */
    public function shouldGetGroupsNames()
    {
        // given
        pattern('(zero) (?<existing>first) and (?<two_existing>second)')
            ->match('zero first and second')
            ->first(function (Detail $detail) {
                // when
                $groupNames = $detail->groups()->names();
                $namedGroups = $detail->namedGroups()->names();

                // then
                $this->assertSame([null, 'existing', 'two_existing'], $groupNames);
                $this->assertSame(['existing', 'two_existing'], $namedGroups);
            });
    }

    /**
     * @test
     */
    public function shouldCount()
    {
        // given
        pattern('(zero) (?<existing>first) and (?<two_existing>second)')
            ->match('zero first and second')
            ->first(function (Detail $detail) {
                // when
                $groups = $detail->groups()->count();
                $namedGroups = $detail->namedGroups()->count();

                // then
                $this->assertSame(3, $groups);
                $this->assertSame(2, $namedGroups);
            });
    }

    /**
     * @test
     */
    public function shouldThrowOnInvalidGroupName()
    {
        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Group name must be an alphanumeric string, not starting with a digit, but '2sd' given");

        // given
        pattern('(?<one>first) and (?<two>second)')
            ->match('first and second')
            ->first(function (Detail $detail) {
                // when
                $detail->hasGroup('2sd');
            });
    }
}

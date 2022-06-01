<?php
namespace Test\Feature\TRegx\CleanRegex\Match\Details\J;

use PHPUnit\Framework\TestCase;
use Test\Utils\AssertsGroup;
use Test\Utils\DetailFunctions;
use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\CleanRegex\Match\Details\Group\MatchedGroup;
use TRegx\CleanRegex\Match\Details\Group\NotMatchedGroup;

class MatchDetailTest extends TestCase
{
    use AssertsGroup;

    /**
     * @test
     */
    public function shouldGroupGetRightValue()
    {
        // when
        pattern('(?<group>Foo)(?<group>Bar)', 'J')
            ->match('FooBar')
            ->first(DetailFunctions::out($detail));
        // given
        $group = $detail->group('group');
        // when, then
        $this->assertSame(['group', null], $detail->groupNames());
        $this->assertSame(1, $group->index());
        $this->assertSame(0, $group->offset());
        $this->assertSame('Foo', $group->text());
        $this->assertSame('Foo', $detail->get('group'));
    }

    /**
     * @test
     */
    public function shouldLastGroupNotBeMatched()
    {
        // given
        pattern('(?:(?<group>Foo)|(?<group>Bar)|(?<group>Lorem))', 'J')
            ->match('Lorem')
            ->first(DetailFunctions::out($detail));
        $group = $detail->group('group');
        // when, then
        $this->assertSame(['group', null, null], $detail->groupNames());
        $this->assertGroupIndex(1, $group);
        $this->assertGroupNotMatched($group);
    }

    /**
     * @test
     */
    public function shouldGetThrow_forUnmatchedGroup()
    {
        // given
        pattern('(?:(?<group>Foo)|(?<group>Bar)|(?<group>Lorem))', 'J')
            ->match('Lorem')
            ->first(DetailFunctions::out($detail));
        // then
        $this->expectException(GroupNotMatchedException::class);
        $this->expectExceptionMessage("Expected to get group 'group', but the group was not matched");
        // when
        $detail->get('group');
    }

    /**
     * @test
     */
    public function shouldStreamHandleDuplicateGroups_notMatched()
    {
        // when
        pattern('(?:(?<group>Foo)|(?<group>Bar)|(?<group>Lorem))', 'J')
            ->match('Lorem')
            ->group('group')
            ->stream()
            ->forEach(function (NotMatchedGroup $group) {
                $this->assertFalse($group->matched());
            });
    }

    /**
     * @test
     */
    public function shouldStreamHandleDuplicateGroups_matched()
    {
        // given
        pattern('(?:(?<group>Foo)|(?<group>Bar)|(?<group>Lorem))', 'J')
            ->match('Foo')
            ->group('group')
            ->stream()
            ->forEach(function (MatchedGroup $group) {
                $this->assertSame('Foo', $group->text());
            });
    }

    /**
     * @test
     */
    public function shouldThrowWithMessage()
    {
        // then
        $this->expectException(GroupNotMatchedException::class);
        $this->expectExceptionMessage("Expected to call text() for group 'group', but the group was not matched");

        // given
        pattern('(?:(?<group>Foo)|(?<group>Bar)|(?<group>Lorem))', 'J')
            ->match('Lorem')
            ->group('group')
            ->forEach(function (NotMatchedGroup $group) {
                // when
                $group->text();
            });
    }

    /**
     * @test
     */
    public function shouldOptionalThrowWithMessage()
    {
        // then
        $this->expectException(GroupNotMatchedException::class);
        $this->expectExceptionMessage("Expected to call text() for group 'group', but the group was not matched");

        // given
        pattern('(?:(?<group>Foo)|(?<group>Bar)|(?<group>Lorem))', 'J')
            ->match('Lorem')
            ->group('group')
            ->forEach(function (NotMatchedGroup $group) {
                // when
                $group->text();
            });
    }

    /**
     * @test
     */
    public function shouldGetIdentifier()
    {
        // given
        pattern('(?:(?<group>Foo)|(?<group>Bar)|(?<group>Lorem))', 'J')
            ->match('Lorem')
            ->group('group')
            ->forEach(function (NotMatchedGroup $group) {
                // when
                $this->assertSame('group', $group->usedIdentifier());
            });
    }

    /**
     * @test
     */
    public function shouldGetGroups()
    {
        // given
        pattern('(?<group>Foo)|(?<group>Bar)|(?<group>Lorem)', 'J')
            ->match('Lorem')
            ->first(DetailFunctions::out($detail));
        // when
        $groups = $detail->groups();
        // then
        $this->assertGroupTextsOptional([null, null, 'Lorem'], $groups);
    }

    /**
     * @test
     */
    public function shouldGetGroupNames()
    {
        // given
        pattern('(?<group>Foo)|(?<group>Bar)|(?<group>Lorem)', 'J')
            ->match('Lorem')
            ->first(DetailFunctions::out($detail));
        // when
        $groups = $detail->groups();
        // then
        $this->assertGroupNames(['group', null, null], $groups);
        $this->assertGroupIndicesConsequetive($groups);
    }

    /**
     * @test
     */
    public function shouldGetNamedGroups()
    {
        // given
        pattern('(?<group>Foo)|(?<group>Bar)|(?<group>Lorem)', 'J')
            ->match('Lorem')
            ->first(DetailFunctions::out($detail));
        // when
        $groups = $detail->namedGroups();
        // then
        $this->assertGroupTextsOptional(['group' => null], $groups);
        $this->assertGroupIndices(['group' => 1], $groups);
    }

    /**
     * @test
     */
    public function shouldGetNamedGroupNames()
    {
        // given
        pattern('(?<group>Foo)|(?<group>Bar)|(?<group>Lorem)', 'J')
            ->match('Lorem')
            ->first(DetailFunctions::out($detail));
        // when
        $groups = $detail->namedGroups();
        // then
        $this->assertGroupNames(['group' => 'group'], $groups);
        $this->assertGroupIndices(['group' => 1], $groups);
    }
}

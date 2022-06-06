<?php
namespace Test\Feature\CleanRegex\Match\Details\J;

use PHPUnit\Framework\TestCase;
use Test\Utils\AssertsGroup;
use Test\Utils\DetailFunctions;
use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\CleanRegex\Match\Details\Detail;
use TRegx\CleanRegex\Match\Details\Group\MatchedGroup;
use TRegx\CleanRegex\Match\Details\Group\NotMatchedGroup;
use TRegx\CleanRegex\Pattern;

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

    /**
     * @test
     */
    public function shouldGet_text()
    {
        // given
        $detail = $this->detail();
        // when
        $declared = $detail->group('group');
        // then
        $this->assertSame('One', $declared->text());
    }

    /**
     * @test
     */
    public function shouldGet_text_or()
    {
        // given
        $detail = $this->detail();
        // when
        $declared = $detail->group('group');
        // then
        $this->assertSame('One', $declared->or('other'));
    }

    /**
     * @test
     */
    public function shouldGetOr_forUnmatchedGroup()
    {
        // given
        Pattern::of('(?<group>Plane)?(?<group>Bird)?Superman', 'J')
            ->match('Superman')
            ->first(DetailFunctions::out($detail));
        // when
        $declared = $detail->group('group');
        // then
        $this->assertSame('other', $declared->or('other'));
    }

    /**
     * @test
     */
    public function shouldGet_get()
    {
        // given
        $detail = $this->detail();
        // when
        $declared = $detail->get('group');
        // then
        $this->assertSame('One', $declared);
    }

    /**
     * @test
     */
    public function shouldGetOffset()
    {
        // given
        $detail = $this->detail();
        // when
        $declared = $detail->group('group');
        // then
        $this->assertSame(0, $declared->offset());
    }

    /**
     * @test
     */
    public function shouldGetName()
    {
        // given
        $detail = $this->detail();
        // when
        $declared = $detail->group('group');
        // then
        $this->assertSame('group', $declared->name());
    }

    /**
     * @test
     */
    public function shouldGetIndex()
    {
        // given
        $detail = $this->detail();
        // when
        $declared = $detail->group('group');
        // then
        $this->assertSame(1, $declared->index());
    }

    public function detail(): Detail
    {
        return pattern('(?<group>One)(?<group>Two)', 'J')
            ->match('OneTwo')
            ->stream()
            ->first();
    }

    /**
     * @test
     */
    public function shouldGetAsInt()
    {
        // given
        Pattern::of('(?<group>123),(?<group>456)', 'J')
            ->match('123,456')
            ->first(DetailFunctions::out($detail));
        // when
        $declared = $detail->group('group');
        // then
        $this->assertSame(123, $declared->toInt());
    }

    /**
     * @test
     */
    public function shouldGetAsIntBase16()
    {
        // given
        Pattern::of('(?<group>123a),(?<group>456a)', 'J')
            ->match('123a,456a')
            ->first(DetailFunctions::out($detail));
        // when
        $declared = $detail->group('group');
        // then
        $this->assertSame(4666, $declared->toInt(16));
    }

    /**
     * @test
     */
    public function shouldBeInt()
    {
        // given
        Pattern::of('(?<group>___),(?<group>123)', 'J')
            ->match('___,123')
            ->first(DetailFunctions::out($detail));
        // when
        $declared = $detail->group('group');
        // then
        $this->assertFalse($declared->isInt());
    }

    /**
     * @test
     */
    public function shouldNotBeInt()
    {
        // given
        Pattern::of('(?<group>123),(?<group>___)', 'J')
            ->match('123,___')
            ->first(DetailFunctions::out($detail));
        // when
        $declared = $detail->group('group');
        // then
        $this->assertTrue($declared->isInt());
    }

    /**
     * @test
     * @deprecated
     */
    public function shouldSubstitute()
    {
        // given
        Pattern::of('<(?<group>Old):(?<group>Old)>', 'J')
            ->match('Subject <Old:Old>.')
            ->first(DetailFunctions::out($detail));
        // when
        $declared = $detail->group('group')->substitute('New');
        // then
        $this->assertEquals('<New:Old>', $declared);
    }
}

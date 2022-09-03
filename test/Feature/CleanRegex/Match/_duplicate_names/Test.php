<?php
namespace Test\Feature\CleanRegex\Match\_duplicate_names;

use PHPUnit\Framework\TestCase;
use Test\Utils\Assertion\AssertsGroup;
use Test\Utils\Functions;
use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\CleanRegex\Match\Detail;
use TRegx\CleanRegex\Pattern;

/**
 * @coversNothing
 */
class Test extends TestCase
{
    use AssertsGroup;

    /**
     * @test
     */
    public function shouldGetGroup()
    {
        // given
        $detail = pattern('(?<group>Foo)(?<group>Bar)', 'J')->match('FooBar')->first();
        // when
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
    public function shouldGetGroupMapped()
    {
        // given
        $pattern = Pattern::of('(?<name>indexed):(?<name>runtime)', 'J');
        $match = $pattern->match('indexed:runtime');
        // when
        [$first] = $match->map(Functions::identity());
        // then
        $this->assertSame('indexed', $first->get('name'));
    }

    /**
     * @test
     */
    public function shouldLastGroupNotBeMatched()
    {
        // given
        $pattern = pattern('(?<group>Foo)|(?<group>Bar)|(?<group>Lorem)', 'J');
        $detail = $pattern->match('Lorem')->first();
        // when
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
        $pattern = pattern('(?<group>Foo)|(?<group>Bar)|(?<group>Lorem)', 'J');
        $detail = $pattern->match('Lorem')->first();
        // then
        $this->expectException(GroupNotMatchedException::class);
        $this->expectExceptionMessage("Expected to get group 'group', but the group was not matched");
        // when
        $detail->get('group');
    }

    /**
     * @test
     */
    public function shouldGetGroups()
    {
        // given
        $pattern = pattern('(?<group>Foo)|(?<group>Bar)|(?<group>Lorem)', 'J');
        $detail = $pattern->match('Lorem')->first();
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
        $pattern = pattern('(?<group>Foo)|(?<group>Bar)|(?<group>Lorem)', 'J');
        $detail = $pattern->match('Lorem')->first();
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
        $pattern = pattern('(?<group>Foo)|(?<group>Bar)|(?<group>Lorem)', 'J');
        $detail = $pattern->match('Lorem')->first();
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
        $pattern = pattern('(?<group>Foo)|(?<group>Bar)|(?<group>Lorem)', 'J');
        $detail = $pattern->match('Lorem')->first();
        // when
        $groups = $detail->namedGroups();
        // then
        $this->assertGroupNames(['group' => 'group'], $groups);
        $this->assertGroupIndices(['group' => 1], $groups);
    }

    /**
     * @test
     */
    public function shouldGetGroupNamesDuplicateNamed()
    {
        // when
        $groupNames = Pattern::of('(?<name>Foo)(?<name>Foo)', 'J')->match('Foo')->groupNames();
        // then
        $this->assertSame(['name', null], $groupNames);
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
        $pattern = Pattern::of('(?<group>Plane)?(?<group>Bird)?Superman', 'J');
        $detail = $pattern->match('Superman')->first();
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
        $pattern = pattern('(?<group>One),(?<group>Two)', 'J');
        return $pattern->match('One,Two')->first();
    }

    /**
     * @test
     */
    public function shouldGetAsInt()
    {
        // given
        $pattern = Pattern::of('(?<group>123),(?<group>456)', 'J');
        $detail = $pattern->match('123,456')->first();
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
        $pattern = Pattern::of('(?<group>123a),(?<group>456a)', 'J');
        $detail = $pattern->match('123a,456a')->first();
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
        $pattern = Pattern::of('(?<group>___),(?<group>123)', 'J');
        $detail = $pattern->match('___,123')->first();
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
        $pattern = Pattern::of('(?<group>123),(?<group>___)', 'J');
        $detail = $pattern->match('123,___')->first();
        // when
        $declared = $detail->group('group');
        // then
        $this->assertTrue($declared->isInt());
    }

    /**
     * @test
     */
    public function shouldGetGroupMatched()
    {
        // given
        $pattern = Pattern::of('Foo (?<name>bad){0}(?<name>good)', 'J');
        $match = $pattern->match('Foo good');
        // when
        [$first] = $match->map(Functions::identity());
        // then
        $this->assertFalse($first->group('name')->matched());
    }
}

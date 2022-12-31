<?php
namespace Test\Feature\CleanRegex\replace\callback\Detail\groups;

use PHPUnit\Framework\TestCase;
use Test\Utils\Assertion\AssertsGroup;
use Test\Utils\Functions;
use TRegx\CleanRegex\Pattern;

class Test extends TestCase
{
    use AssertsGroup;

    /**
     * @test
     */
    public function shouldGetGroups_text()
    {
        // given
        $pattern = Pattern::of('(Bifur),(Bofur),(Bombur){0}', 'i');
        $pattern->replace('Bifur,Bofur,')->callback(Functions::out($detail, ''));
        // when
        $groups = $detail->groups();
        // then
        $this->assertGroupTextsOptional(['Bifur', 'Bofur', null], $groups);
    }

    /**
     * @test
     */
    public function shouldGetGroups_consequetive()
    {
        // given
        $pattern = Pattern::of('(Bifur),(Bofur),(Bombur){0}', 'i');
        $pattern->replace('Bifur,Bofur,')->callback(Functions::out($detail, ''));
        // when
        $groups = $detail->groups();
        // then
        $this->assertGroupIndicesConsequetive($groups);
    }

    /**
     * @test
     */
    public function shouldGetGroups_offset()
    {
        // given
        Pattern::of('(12€)(cm)', 'i')->replace('€€ 12€cm')->callback(Functions::out($detail, ''));
        // when
        $groups = $detail->groups();
        // then
        $this->assertGroupOffsets([3, 6], $groups);
        $this->assertGroupByteOffsets([7, 12], $groups);
    }

    /**
     * @test
     *
     */
    public function shouldGetGroups_forUnmatchedGroup()
    {
        // when
        pattern('Foo(Bar){0}(Cat){0}')->replace('Foo')->callback(Functions::out($detail, ''));
        // when
        [$first, $second] = $detail->groups();
        // then
        $this->assertGroupNotMatched($first);
        $this->assertGroupNotMatched($second);
    }

    /**
     * @test
     */
    public function shouldGetGroups_empty()
    {
        // given
        Pattern::of('Foo')->replace('Foo')->callback(Functions::out($detail, ''));
        // when
        $groups = $detail->groups();
        // then
        $this->assertSame([], $groups);
    }

    /**
     * @test
     */
    public function shouldGetGroupsNames()
    {
        // given
        $pattern = pattern('(zero), (?<one>first) and (?<two>second)');
        $pattern->replace('zero, first and second')->callback(Functions::out($detail, ''));
        // when, then
        $this->assertSame([null, 'one', 'two'], $detail->groupNames());
        $this->assertGroupNames([null, 'one', 'two'], $detail->groups());
    }
}

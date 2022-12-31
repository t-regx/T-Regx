<?php
namespace Test\Feature\CleanRegex\replace\withGroup;

use PHPUnit\Framework\TestCase;
use Test\Utils\Backtrack\CausesBacktracking;
use Test\Utils\TestCase\TestCaseExactMessage;
use Test\Utils\TestCase\TestCasePasses;
use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\CleanRegex\Exception\NonexistentGroupException;
use TRegx\CleanRegex\Pattern;
use TRegx\Exception\MalformedPatternException;

/**
 * @coversNothing
 */
class Test extends TestCase
{
    use TestCaseExactMessage, CausesBacktracking, TestCasePasses;

    /**
     * @test
     */
    public function shouldReplace()
    {
        // when
        $replaced = pattern('(\d+)[cm]m')->replace('13cm 18m 19cm')->withGroup(1);
        // then
        $this->assertSame('13 18m 19', $replaced);
    }

    /**
     * @test
     */
    public function shouldReplace_namedGroup()
    {
        // when
        $replaced = pattern('\d+(?<unit>[cm]m)')->replace('14cm 17m 19mm')->withGroup('unit');
        // then
        $this->assertSame('cm 17m mm', $replaced);
    }

    /**
     * @test
     */
    public function shouldReplace_whole()
    {
        // given
        $pattern = pattern('https?://(google|facebook)\.com');
        $subject = 'Links: https://google.com and http://facebook.com';
        // when
        $replaced = $pattern->replace($subject)->withGroup(0);
        // then
        $this->assertSame($subject, $replaced);
    }

    /**
     * @test
     */
    public function shouldThrow_forNegativeGroupIndex()
    {
        // then
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Group index must be a non-negative integer, but -6 given');
        // when
        pattern('\d+(?<unit>[cm]m)')->replace('14cm 17m 19mm')->withGroup(-6);
    }

    /**
     * @test
     */
    public function shouldThrow_digitString()
    {
        // then
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Group name must be an alphanumeric string, not starting with a digit, but '6digit' given");
        // when
        pattern('\d+(?<unit>[cm]m)')->replace('14cm 17m 19mm')->withGroup('6digit');
    }

    /**
     * @test
     */
    public function shouldThrow_forInvalidGroupType()
    {
        // then
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Group index must be an integer or a string, but boolean (true) given");
        // when
        pattern('\d+(?<unit>[cm]m)')->replace('14cm 17m 19mm')->withGroup(true);
    }

    /**
     * @test
     */
    public function shouldThrow_NonexistentGroupIndex()
    {
        // then
        $this->expectException(NonexistentGroupException::class);
        $this->expectExceptionMessage('Nonexistent group: #2');
        // when
        pattern('matched')->replace('matched')->withGroup(2);
    }

    /**
     * @test
     */
    public function shouldThrow_NonexistentGroupName()
    {
        // then
        $this->expectException(NonexistentGroupException::class);
        $this->expectExceptionMessage("Nonexistent group: 'unit'");
        // when
        pattern('matched')->replace('matched')->withGroup('unit');
    }

    /**
     * @test
     */
    public function shouldThrow_OnUnmatchedSubject_NonexistentGroupIndex()
    {
        // then
        $this->expectException(NonexistentGroupException::class);
        $this->expectExceptionMessage('Nonexistent group: #2');
        // when
        pattern('Foo')->replace('not matched')->withGroup(2);
    }

    /**
     * @test
     */
    public function shouldThrow_OnUnmatchedSubject_NonexistentGroupName()
    {
        // then
        $this->expectException(NonexistentGroupException::class);
        $this->expectExceptionMessage("Nonexistent group: 'unit'");
        // when
        pattern('Foo')->replace('not matched')->withGroup('unit');
    }

    /**
     * @test
     */
    public function shouldReplace_withLastEmptyGroup()
    {
        // when
        $replaced = pattern('(Foo):()')->replace('Bar')->withGroup(2);
        // then
        $this->assertSame('Bar', $replaced);
    }

    /**
     * @test
     */
    public function shouldThrowForMalformedPattern()
    {
        // then
        $this->expectException(MalformedPatternException::class);
        // when
        pattern(')')->replace('Bar')->withGroup(2);
    }

    /**
     * @test
     * @dataProvider groups
     * @param string|int $groupIdentifier
     * @param string $group
     */
    public function shouldThrow_forUnmatchedGroup($groupIdentifier, string $group)
    {
        // given
        $pattern = pattern('Foo(?<name>Bar){0}');
        // then
        $this->expectException(GroupNotMatchedException::class);
        $this->expectExceptionMessage("Expected to replace with group $group, but the group was not matched");
        // when
        $pattern->replace('Foo')->withGroup($groupIdentifier);
    }

    public function groups(): array
    {
        return [['name', "'name'"], [1, '#1']];
    }

    /**
     * @test
     */
    public function shouldThrow_ForUnmatchedGroupName()
    {
        // then
        $this->expectException(GroupNotMatchedException::class);
        $this->expectExceptionMessage("Expected to replace with group 'named', but the group was not matched");
        // when
        pattern('Foo(?<named>Bar)?')->replace('Foo')->withGroup('named');
    }

    /**
     * @test
     */
    public function shouldThrow_ForUnmatchedGroup_Middle_Indexed()
    {
        // then
        $this->expectException(GroupNotMatchedException::class);
        $this->expectExceptionMessage('Expected to replace with group #1, but the group was not matched');
        // when
        pattern('Foo(Bar){0}(Cat)')->replace('FooCat')->withGroup(1);
    }

    /**
     * @test
     */
    public function shouldThrow_forUnmatchedGroup_Middle_Name()
    {
        // then
        $this->expectException(GroupNotMatchedException::class);
        $this->expectExceptionMessage("Expected to replace with group 'bar', but the group was not matched");
        // when
        pattern('Foo(?<bar>Bar){0}(?<car>Car)')->replace('FooCar')->withGroup('bar');
    }

    /**
     * @test
     */
    public function shouldReplace_withEmptyGroup()
    {
        // when
        $replaced = pattern('Foo(?<group>)(?<car>Car)')->replace('"FooCar"')->withGroup('group');
        // then
        $this->assertSame('""', $replaced);
    }

    /**
     * @test
     */
    public function shouldReplace_withEmptyGroup_thirdMatch()
    {
        // when
        $replaced = pattern('Foo(?<group>)(?<car>Car)')->replace('"FooCar", "FooCar", "FooCar"')->withGroup('group');
        // then
        $this->assertSame('"", "", ""', $replaced);
    }

    /**
     * @test
     */
    public function shouldThrow_forUnmatchedGroup_lastGroup()
    {
        // given
        $pattern = pattern('Foo(?<bar>Bar){0}');
        // then
        $this->expectException(GroupNotMatchedException::class);
        $this->expectExceptionMessage("Expected to replace with group 'bar', but the group was not matched");
        // when
        $pattern->replace('Foo')->withGroup('bar');
    }

    /**
     * @test
     */
    public function shouldThrow_groupNotMatch_lastGroup_thirdIndex()
    {
        // given
        $pattern = pattern('(?<foo>Foo)(?<bar>Bar)?(?<last>;)');
        $replace = $pattern->replace('FooBar; FooBar; Foo; Foo;');
        // then
        $this->expectException(GroupNotMatchedException::class);
        $this->expectExceptionMessage("Expected to replace with group 'bar', but the group was not matched");
        // when
        $replace->withGroup('bar');
    }

    /**
     * @test
     * @depends shouldThrow_groupNotMatch_lastGroup_thirdIndex
     */
    public function shouldThrow_groupNotMatch_lastGroup_thirdIndex_breaking()
    {
        // given
        $pattern = pattern('(?<foo>Foo)(?<bar>Bar)?(?<last>;)');
        $replace = $pattern->replace('FooBar; FooBar; Foo; Foo;');

        try {
            $replace->withGroup('bar');
        } catch (GroupNotMatchedException $ignored) {
        }

        // then
        $this->expectException(GroupNotMatchedException::class);
        $this->expectExceptionMessage("Expected to replace with group 'bar', but the group was not matched");
        // when
        $replace->withGroup('bar');
    }

    /**
     * @test
     */
    public function shouldThrow_groupNotMatch_lastGroup_secondIndex()
    {
        // given
        $pattern = pattern('(?<foo>Foo)(?<bar>Bar)?(?<last>;)');
        $replace = $pattern->replace('FooBar; Foo;');
        // then
        $this->expectException(GroupNotMatchedException::class);
        $this->expectExceptionMessage("Expected to replace with group 'bar', but the group was not matched");
        // when
        $replace->withGroup('bar');
    }

    /**
     * @test
     */
    public function shouldThrow_groupNotMatch_secondIndex_afterEmptyGroup_lastGroup()
    {
        // given
        $pattern = pattern('>("(?<empty>)")?');
        $replace = $pattern->replace('>"", >unmatched');
        // then
        $this->expectException(GroupNotMatchedException::class);
        $this->expectExceptionMessage("Expected to replace with group 'empty', but the group was not matched");
        // when
        $replace->withGroup('empty');
    }

    /**
     * @test
     */
    public function shouldThrow_groupNotMatch_secondIndex_afterEmptyGroup()
    {
        // given
        $pattern = pattern('>("(?<empty>)")?()');
        $replace = $pattern->replace('>"", >unmatched');
        // then
        $this->expectException(GroupNotMatchedException::class);
        $this->expectExceptionMessage("Expected to replace with group 'empty', but the group was not matched");
        // when
        $replace->withGroup('empty');
    }

    /**
     * @test
     */
    public function shouldReplace_limit()
    {
        // given
        $replace = Pattern::of('Foo(Bar)')->replace('FooBar, FooBar, FooBar');
        // when
        $replaced = $replace->limit(2)->withGroup(1);
        // then
        $this->assertSame('Bar, Bar, FooBar', $replaced);
    }

    /**
     * @test
     */
    public function shouldReplace_limit_IgnoreCatastrphicBacktracking()
    {
        // given
        $pattern = Pattern::of('(?<empty>)(?:\d+\d+)+3');
        $subject = $this->backtrackingSubject(2);
        $replace = $pattern->replace($subject);
        // when
        $replace->limit(2)->withGroup('empty');
        // then
        $this->pass();
    }

    /**
     * @test
     */
    public function shouldReplace_limit_IgnoreCatastrphicRecursion()
    {
        // given
        $pattern = Pattern::of('(*LIMIT_RECURSION=4)(?<empty>)(one|two|a(b((c(d)))))');
        $replace = $pattern->replace('one, two, abcd');
        // given
        $replace->limit(2)->withGroup('empty');
        // then
        $this->pass();
    }
}

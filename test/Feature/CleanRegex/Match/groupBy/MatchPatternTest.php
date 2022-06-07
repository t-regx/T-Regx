<?php
namespace Test\Feature\CleanRegex\Match\groupBy;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Test\Utils\Assertion\AssertsDetail;
use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\CleanRegex\Exception\NonexistentGroupException;
use TRegx\CleanRegex\Match\Details\Detail;
use TRegx\CleanRegex\Pattern;
use function pattern;

/**
 * @covers \TRegx\CleanRegex\Match\MatchPattern
 */
class MatchPatternTest extends TestCase
{
    use AssertsDetail;

    /**
     * @test
     */
    public function shouldGroupBy()
    {
        // when
        $groupped = pattern('\d+(?<unit>cm|mm)?')->match('14cm 13mm 19cm 18mm 2cm')->groupBy('unit');
        // then
        [$first, $second, $third] = $groupped['cm'];
        [$fourth, $fifth] = $groupped['mm'];
        $this->assertDetailText('14cm', $first);
        $this->assertDetailText('19cm', $second);
        $this->assertDetailText('2cm', $third);
        $this->assertDetailText('13mm', $fourth);
        $this->assertDetailText('18mm', $fifth);
    }

    /**
     * @test
     */
    public function shouldDetailGetSubject()
    {
        // when
        $groupped = pattern('Foo')->match('subject:Foo')->groupBy(0);
        // then
        [$detail] = $groupped['Foo'];
        $this->assertDetailSubject('subject:Foo', $detail);
    }

    /**
     * @test
     */
    public function shouldThrowForUnmatchedGroupIndex()
    {
        // then
        $this->expectException(GroupNotMatchedException::class);
        $this->expectExceptionMessage('Expected to group matches by group #1, but the group was not matched');
        // when
        pattern('Foo(Bar)?')->match('FooBar, Foo')->groupBy(1);
    }

    /**
     * @test
     */
    public function shouldThrowForUnmatchedGroupName()
    {
        // then
        $this->expectException(GroupNotMatchedException::class);
        $this->expectExceptionMessage("Expected to group matches by group 'group', but the group was not matched");
        // when
        pattern('Foo(?<group>Bar)?')->match('FooBar, Foo')->groupBy('group');
    }

    /**
     * @test
     */
    public function shouldThrowForInvalidGroupName()
    {
        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Group name must be an alphanumeric string, not starting with a digit, but '2bar' given");
        // when
        Pattern::of('(?<foo>foo)')->match('foo')->groupBy('2bar');
    }

    /**
     * @test
     */
    public function shouldThrowForNegativeGroupIndex()
    {
        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Group index must be a non-negative integer, but -1 given");
        // when
        Pattern::of('(?<foo>foo)')->match('foo')->groupBy(-1);
    }

    /**
     * @test
     */
    public function shouldThrowForNonexistentGroup()
    {
        // then
        $this->expectException(NonexistentGroupException::class);
        $this->expectExceptionMessage("Nonexistent group: 'bar'");
        // when
        Pattern::of('(?<foo>foo)')->match('foo')->groupBy('bar');
    }

    /**
     * @test
     */
    public function shouldGroupByCorrectlyByDuplicateName()
    {
        // when
        $result = pattern('(?<one>Foo)(?<one>Bar)', 'J')->match('FooBar')->groupBy('one');
        // then
        /** @var Detail $detail */
        [$detail] = $result['Foo'];
        $this->assertSame('FooBar', $detail->text());
        $this->assertSame('FooBar', $detail->subject());
    }

    /**
     * @test
     */
    public function shouldGroupByCorrectlyThrowForUnmatchedDuplicateName()
    {
        // given
        $match = pattern('(?<one>Foo){0}(?<one>Bar)', 'J')->match('Bar');
        // then
        $this->expectException(GroupNotMatchedException::class);
        $this->expectExceptionMessage("Expected to group matches by group 'one', but the group was not matched");
        // when
        $match->groupBy('one');
    }

    /**
     * @test
     */
    public function shouldGroupByEmptyElement()
    {
        // when
        $groupped = pattern('()')->match('')->groupBy(1);
        // then
        [$detail] = $groupped[''];
        $this->assertDetailText('', $detail);
    }
}

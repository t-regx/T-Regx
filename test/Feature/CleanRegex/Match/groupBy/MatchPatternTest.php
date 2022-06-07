<?php
namespace Test\Feature\CleanRegex\Match\groupBy;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Test\Utils\Assertion\AssertsDetail;
use Test\Utils\Structure\AssertsStructure;
use Test\Utils\Structure\Expect;
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
    use AssertsDetail, AssertsStructure;

    /**
     * @test
     */
    public function shouldGroupBy()
    {
        // when
        $groupped = pattern('\d+(?<unit>cm|mm)?')->match('14cm 13mm 19cm 18mm 2cm')->groupBy('unit');
        // then
        $this->assertStructure($groupped, [
            'cm' => [Expect::text('14cm'), Expect::text('19cm'), Expect::text('2cm')],
            'mm' => [Expect::text('13mm'), Expect::text('18mm')],
        ]);
    }

    /**
     * @test
     */
    public function shouldDetailGetSubject()
    {
        // when
        $groupped = pattern('Foo')->match('subject:Foo')->groupBy(0);
        // then
        $this->assertStructure($groupped, [
            'Foo' => [Expect::subject('subject:Foo')]
        ]);
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
        $this->assertStructure($groupped, [
            '' => [Expect::text('')]
        ]);
    }
}

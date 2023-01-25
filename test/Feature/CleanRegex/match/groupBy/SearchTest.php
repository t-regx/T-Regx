<?php
namespace Test\Feature\CleanRegex\match\groupBy;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Test\Utils\Assertion\AssertsDetail;
use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\CleanRegex\Exception\NonexistentGroupException;
use TRegx\CleanRegex\Pattern;

/**
 * @covers \TRegx\CleanRegex\Match\Search
 */
class SearchTest extends TestCase
{
    use AssertsDetail;

    /**
     * @test
     */
    public function shouldGroupBy()
    {
        // when
        $grouped = Pattern::of('\d+(?<unit>cm|mm)?')->search('14cm 13mm 19cm 18mm 2cm')->groupBy('unit');
        // then
        $expected = [
            'cm' => ['14cm', '19cm', '2cm'],
            'mm' => ['13mm', '18mm']
        ];
        $this->assertSame($expected, $grouped);
    }

    /**
     * @test
     */
    public function shouldGroupByIndexedGroup()
    {
        // when
        $grouped = Pattern::of('\d+(?<unit>cm|mm)?')->search('14cm 13mm 19cm 18mm 2cm')->groupBy(1);
        // then
        $expected = [
            'cm' => ['14cm', '19cm', '2cm'],
            'mm' => ['13mm', '18mm']
        ];
        $this->assertSame($expected, $grouped);
    }

    /**
     * @test
     */
    public function shouldGroupBy_onUnmatchedSubject()
    {
        // when
        $grouped = Pattern::of('(Foo)')->search('Bar')->groupBy(1);
        // then
        $this->assertEmpty($grouped);
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
        Pattern::of('Foo(Bar)?')->search('FooBar, Foo')->groupBy(1);
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
        Pattern::of('Foo(?<group>Bar)?')->search('FooBar, Foo')->groupBy('group');
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
        Pattern::of('(?<foo>foo)')->search('foo')->groupBy('2bar');
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
        Pattern::of('(?<foo>foo)')->search('foo')->groupBy(-1);
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
        Pattern::of('(?<foo>foo)')->search('foo')->groupBy('bar');
    }

    /**
     * @test
     */
    public function shouldThrowForMalformedGroup()
    {
        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Group name must be an alphanumeric string, not starting with a digit, but '2bar' given");
        // when
        Pattern::of('(?<foo>foo)')->search('foo')->groupBy('2bar');
    }

    /**
     * @test
     */
    public function shouldGroupByEmptyElement()
    {
        // when
        $grouped = Pattern::of('()')->search('')->groupBy(1);
        // then
        $this->assertSame(['' => ['']], $grouped);
    }

    /**
     * @test
     */
    public function shouldGroupByCorrectlyByDuplicateName()
    {
        // when
        $result = Pattern::of('(?<one>Foo)(?<one>Bar)', 'J')->search('FooBar')->groupBy('one');
        // then
        [$detail] = $result['Foo'];
        $this->assertSame('FooBar', $detail);
    }

    /**
     * @test
     */
    public function shouldGroupByCorrectlyThrowForUnmatchedDuplicateName()
    {
        // given
        $match = Pattern::of('(?<one>Foo){0}(?<one>Bar)', 'J')->search('Bar');
        // then
        $this->expectException(GroupNotMatchedException::class);
        $this->expectExceptionMessage("Expected to group matches by group 'one', but the group was not matched");
        // when
        $match->groupBy('one');
    }
}

<?php
namespace Test\Feature\TRegx\CleanRegex\Match\groupBy;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\CleanRegex\Exception\NonexistentGroupException;
use TRegx\CleanRegex\Pattern;
use function pattern;

class MatchPatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGroupBy()
    {
        // when
        $result = pattern('\d+(?<unit>cm|mm)?')->match('14cm 13mm 19cm 18mm 2cm')->groupBy('unit');
        // then
        [$first, $second, $third] = $result['cm'];
        [$fourth, $fifth] = $result['mm'];
        $this->assertSame('14cm', $first->text());
        $this->assertSame('19cm', $second->text());
        $this->assertSame('2cm', $third->text());
        $this->assertSame('13mm', $fourth->text());
        $this->assertSame('18mm', $fifth->text());
    }

    /**
     * @test
     */
    public function shouldDetailGetSubject()
    {
        // when
        $result = pattern('Foo')->match('subject:Foo')->groupBy(0);
        // then
        [$detail] = $result['Foo'];
        $this->assertSame('subject:Foo', $detail->subject());
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
}

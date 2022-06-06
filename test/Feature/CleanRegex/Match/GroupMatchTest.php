<?php
namespace Test\Feature\CleanRegex\Match;

use PHPUnit\Framework\TestCase;
use Test\Utils\ExactExceptionMessage;
use Test\Utils\Functions;
use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\CleanRegex\Exception\InvalidReturnValueException;
use TRegx\CleanRegex\Exception\NonexistentGroupException;
use TRegx\CleanRegex\Exception\NoSuchNthElementException;
use TRegx\CleanRegex\Pattern;

/**
 * @covers \TRegx\CleanRegex\Match\GroupMatch
 */
class GroupMatchTest extends TestCase
{
    use ExactExceptionMessage;

    /**
     * @test
     */
    public function shouldFlatMap()
    {
        // given
        $group = Pattern::of('"([\w ]+)"')->match('"Foo Bar", "Lorem"')->group(1);
        // when
        $letters = $group->flatMap(Functions::letters());
        // then
        $this->assertSame(['F', 'o', 'o', ' ', 'B', 'a', 'r', 'L', 'o', 'r', 'e', 'm'], $letters);
    }

    /**
     * @test
     */
    public function shouldFlatMapAssoc()
    {
        // given
        $group = Pattern::of('"([\w ]+)"')->match('"Lorem", "Dog", "C"')->group(1);
        // when
        $letters = $group->flatMapAssoc(Functions::letters());
        // then
        $this->assertSame(['C', 'o', 'g', 'e', 'm'], $letters);
    }

    /**
     * @test
     */
    public function shouldThrow_flatMap_forInvalidArgument()
    {
        // given
        $group = Pattern::of('(Valar)')->match('Valar Morghulis')->group(1);
        // then
        $this->expectException(InvalidReturnValueException::class);
        $this->expectExceptionMessage("Invalid flatMap() callback return type. Expected array, but string ('text') given");
        // when
        $group->flatMap(Functions::constant('text'));
    }

    /**
     * @test
     */
    public function shouldThrow_flatMapAssoc_forInvalidArgument()
    {
        // given
        $group = Pattern::of('(Valar)')->match('Valar Morghulis')->group(1);
        // then
        $this->expectException(InvalidReturnValueException::class);
        $this->expectExceptionMessage("Invalid flatMapAssoc() callback return type. Expected array, but string ('word') given");
        // when
        $group->flatMapAssoc(Functions::constant('word'));
    }

    /**
     * @test
     */
    public function shouldGet_nth_forIndex0()
    {
        // given
        $group = Pattern::of('(Valar)')->match('Valar Morghulis')->group(1);
        // when
        $nth = $group->nth(0);
        // then
        $this->assertSame('Valar', $nth);
    }

    /**
     * @test
     */
    public function shouldThrowForNthGroupUnmatched()
    {
        // given
        $group = pattern('Foo(?<group>Bar)?')
            ->match('Foo')
            ->group('group');
        // then
        $this->expectException(GroupNotMatchedException::class);
        $this->expectExceptionMessage("Expected to get group 'group' from the 0-nth match, but the group was not matched");
        // when
        $group->nth(0);
    }

    /**
     * @test
     */
    public function shouldNth_forIndex1_insufficient()
    {
        // given
        $group = Pattern::of('(?<curse>Valar)')->match('Valar Morghulis')->group('curse');
        // then
        $this->expectException(NoSuchNthElementException::class);
        $this->expectExceptionMessage("Expected to get group 'curse' from the 1-nth match, but only 1 occurrences were matched");
        // when
        $group->nth(1);
    }

    /**
     * @test
     */
    public function shouldNth_forIndex2()
    {
        // given
        $group = Pattern::of('(Valar)()()')->match('Valar Morghulis, Valar Dohaeris')->group(3);
        // then
        $this->expectException(NoSuchNthElementException::class);
        $this->expectExceptionMessage("Expected to get group #3 from the 2-nth match, but only 2 occurrences were matched");
        // when
        $group->nth(2);
    }

    /**
     * @test
     */
    public function shouldThrow_nth_forNonexistentGroup()
    {
        // given
        $group = Pattern::of('(?<value>Foo)')->match('Foo')->group('missing');
        // then
        $this->expectException(NonexistentGroupException::class);
        $this->expectExceptionMessage("Nonexistent group: 'missing'");
        // when
        $group->nth(0);
    }

    /**
     * @test
     */
    public function shouldThrow_nth_forNegativeIndex()
    {
        // given
        $group = Pattern::of('(?<value>Foo)')->match('Foo')->group('value');
        // then
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Negative group nth: -3");
        // when
        $group->nth(-3);
    }

    /**
     * @test
     */
    public function shouldThrow_nth_forUnmatchedSubject()
    {
        // given
        $group = Pattern::of('(?<value>Foo)')->match('Bar')->group('value');
        // then
        $this->expectException(NoSuchNthElementException::class);
        $this->expectExceptionMessage("Expected to get group 'value' from the 3-nth match, but the subject was not matched at all");
        // when
        $group->nth(3);
    }

    /**
     * @test
     * @depends shouldThrow_nth_forNonexistentGroup
     * @depends shouldThrow_nth_forNegativeIndex
     * @depends shouldThrow_nth_forUnmatchedSubject
     */
    public function shouldThrow_forNonexistentGroup_overNegativeIndex()
    {
        // given
        $group = Pattern::of('(?<value>Foo)')->match('Bar')->group('missing');
        // then
        $this->expectException(NonexistentGroupException::class);
        $this->expectExceptionMessage("Nonexistent group: 'missing'");
        // when
        $group->nth(-3);
    }

    /**
     * @test
     * @depends shouldThrow_nth_forUnmatchedSubject
     */
    public function shouldNthThrowForUnmatchedSubject_5th()
    {
        // given
        $group = Pattern::of('(?<name>Foo)')->match('Bar')->group('name');
        // then
        $this->expectException(NoSuchNthElementException::class);
        $this->expectExceptionMessage("Expected to get group 'name' from the 5-nth match, but the subject was not matched at all");
        // when
        $group->nth(5);
    }

    /**
     * @test
     */
    public function shouldNthThrowForUnmatchedSubject_stream()
    {
        // given
        $group = Pattern::of('(?<value>Foo)')->match('Bar')->group('value')->stream();
        // then
        $this->expectException(NoSuchNthElementException::class);
        $this->expectExceptionMessage("Expected to get the 3-nth stream element, but the subject backing the stream was not matched");
        // when
        $group->nth(3);
    }
}

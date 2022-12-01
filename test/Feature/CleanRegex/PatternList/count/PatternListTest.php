<?php
namespace Test\Feature\CleanRegex\PatternList\count;

use PHPUnit\Framework\TestCase;
use Test\Utils\Backtrack\CausesBacktracking;
use TRegx\CleanRegex\Exception\PatternMalformedPatternException;
use TRegx\CleanRegex\Pattern;
use TRegx\Exception\MalformedPatternException;
use TRegx\SafeRegex\Exception\CatastrophicBacktrackingException;

/**
 * @covers \TRegx\CleanRegex\PatternList::count
 */
class PatternListTest extends TestCase
{
    use CausesBacktracking;

    /**
     * @test
     */
    public function shouldCountMany()
    {
        // given
        $list = Pattern::list(['\d+']);
        // when, then
        $this->assertSame(3, $list->count('12, 15, 16'));
    }

    /**
     * @test
     */
    public function shouldCountMatchedFoo()
    {
        // given
        $list = Pattern::list(['Fo{2}']);
        // when, then
        $this->assertSame(1, $list->count('Foo'));
    }

    /**
     * @test
     */
    public function shouldCountMatchedWhite()
    {
        // given
        $list = Pattern::list(['white']);
        // when, then
        $this->assertSame(1, $list->count('Gandalf, the white'));
    }

    /**
     * @test
     */
    public function shouldCountUnmatchedPattern()
    {
        // given
        $list = Pattern::list(['Foo']);
        // when, then
        $this->assertSame(0, $list->count('John'));
        $this->assertSame(0, $list->count('Mark'));
    }

    /**
     * @test
     */
    public function shouldCountManyPatterns()
    {
        // given
        $list = Pattern::list([
            Pattern::of('Orange'),
            Pattern::inject('@', ['Apple']),
            Pattern::literal('Banana')
        ]);
        // when, then
        $this->assertSame(5, $list->count('Banana, Apple, Orange, Apple, Banana'));
        $this->assertSame(3, $list->count('Banana, Apple, Apple'));
    }

    /**
     * @test
     */
    public function shouldCountManyStringPatterns()
    {
        // given
        $list = Pattern::list(['Orange', 'Apple', 'Banana']);
        // when, then
        $this->assertSame(5, $list->count('Banana, Apple, Orange, Apple, Banana'));
        $this->assertSame(3, $list->count('Banana, Apple, Apple'));
    }

    /**
     * @test
     */
    public function shouldThrowForMalformedPattern()
    {
        // given
        $list = Pattern::list(['+']);
        // then
        $this->expectException(MalformedPatternException::class);
        $this->expectExceptionMessage('Quantifier does not follow a repeatable item at offset 0');
        // when, then
        $list->count('Fail');
    }

    /**
     * @test
     */
    public function shouldThrowForMalformedPatternMiddle()
    {
        // given
        $list = Pattern::list(['Foo', '+']);
        // then
        $this->expectException(MalformedPatternException::class);
        $this->expectExceptionMessage('Quantifier does not follow a repeatable item at offset 0');
        // when, then
        $list->count('Fail');
    }

    /**
     * @test
     */
    public function shouldThrowForMalformedPatternTemplate()
    {
        // given
        $list = Pattern::list(['Foo\\']);
        // then
        $this->expectException(MalformedPatternException::class);
        $this->expectExceptionMessage('Pattern may not end with a trailing backslash');
        // when, then
        $list->count('Fail');
    }

    /**
     * @test
     */
    public function shouldThrowForCatastrophicBacktracking()
    {
        // given
        $list = Pattern::list([
            $this->backtrackingPattern()
        ]);
        // then
        $this->expectException(CatastrophicBacktrackingException::class);
        // when, then
        $list->count($this->backtrackingSubject(0));
    }

    /**
     * @test
     */
    public function shouldPreferTemplateMalformedPattern()
    {
        // given
        $list = Pattern::list(['+', 'Foo\\']);
        // then
        $this->expectException(PatternMalformedPatternException::class);
        $this->expectExceptionMessage('Pattern may not end with a trailing backslash');
        // when, then
        $list->count('Fail');
    }
}

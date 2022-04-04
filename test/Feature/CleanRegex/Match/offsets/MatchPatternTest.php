<?php
namespace Test\Feature\TRegx\CleanRegex\Match\offsets;

use PHPUnit\Framework\TestCase;
use Test\Utils\ExampleException;
use Test\Utils\Functions;
use TRegx\CleanRegex\Exception\NoSuchNthElementException;
use TRegx\CleanRegex\Exception\NoSuchStreamElementException;
use TRegx\CleanRegex\Exception\SubjectNotMatchedException;
use function pattern;

class MatchPatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldThrow_first_OnUnmatchedSubject()
    {
        // then
        $this->expectException(SubjectNotMatchedException::class);
        $this->expectExceptionMessage('Expected to get the first match offset, but subject was not matched');

        // when
        pattern('Foo')->match('Bar')->offsets()->first();
    }

    /**
     * @test
     */
    public function shouldThrow_nth_OnUnmatchedSubject()
    {
        // then
        $this->expectException(NoSuchNthElementException::class);
        $this->expectExceptionMessage('Expected to get the 0-nth match offset, but subject was not matched');

        // when
        pattern('Foo')->match('Bar')->offsets()->nth(0);
    }

    /**
     * @test
     */
    public function shouldThrow_map_first_OnUnmatchedSubject()
    {
        // then
        $this->expectException(NoSuchStreamElementException::class);
        $this->expectExceptionMessage('Expected to get the first match offset, but subject was not matched');

        // when
        pattern('Foo')->match('Bar')->offsets()->map(Functions::fail())->first();
    }

    /**
     * @test
     */
    public function shouldThrow_map_nth_OnUnmatchedSubject()
    {
        // then
        $this->expectException(NoSuchNthElementException::class);
        $this->expectExceptionMessage('Expected to get the 0-nth stream element, but the subject backing the stream was not matched');

        // when
        pattern('Foo')->match('Bar')->offsets()->map(Functions::fail())->nth(0);
    }

    /**
     * @test
     */
    public function shouldThrow_filter_first()
    {
        // then
        $this->expectException(NoSuchStreamElementException::class);
        $this->expectExceptionMessage('Expected to get the first stream element, but the stream has 0 element(s)');

        // when
        pattern('\d+')->match('12')->offsets()->filter(Functions::constant(false))->first();
    }

    /**
     * @test
     */
    public function shouldThrow_filter_nth()
    {
        // then
        $this->expectException(NoSuchNthElementException::class);
        $this->expectExceptionMessage('Expected to get the 2-nth stream element, but the stream has 0 element(s)');

        // when
        pattern('\d+')->match('13 14')->offsets()->filter(Functions::constant(false))->nth(2);
    }

    /**
     * @test
     */
    public function shouldThrow_nth()
    {
        // then
        $this->expectException(NoSuchNthElementException::class);
        $this->expectExceptionMessage('Expected to get the 2-nth match offset, but only 2 occurrences are available');

        // when
        pattern('\d+')->match('23 25')->offsets()->nth(2);
    }

    /**
     * @test
     */
    public function shouldThrow_findFirst_orThrow_WithCustomException()
    {
        // then
        $this->expectException(ExampleException::class);
        // when
        pattern('Foo')->match('Bar')->offsets()->findFirst(Functions::fail())->orThrow(new ExampleException());
    }

    /**
     * @test
     */
    public function shouldThrow_findNth_orThrow_WithCustomException()
    {
        // then
        $this->expectException(ExampleException::class);
        // when
        pattern('\d+')->match('12 13 14')->offsets()->findNth(5)->orThrow(new ExampleException());
    }

    /**
     * @test
     */
    public function shouldCall_offsets_findFirst_OnUnmatchedPattern_orElse()
    {
        // given
        pattern('(?<sparrow>Foo)')->match('Bar')->offsets()->findFirst(Functions::fail())->orElse(Functions::pass());
    }

    /**
     * @test
     */
    public function shouldCall_offsets_findNth_OnUnmatchedPattern_orElse()
    {
        // given
        pattern('(?<sparrow>Foo)')->match('Bar')->offsets()->findNth(0)->orElse(Functions::pass());
    }

    /**
     * @test
     */
    public function shouldCall_offsets_findNth_OnInfussificientMatch_orElse()
    {
        // given
        pattern('(?<sparrow>Foo)')->match('Foo')->offsets()->findNth(1)->orElse(Functions::pass());
    }
}

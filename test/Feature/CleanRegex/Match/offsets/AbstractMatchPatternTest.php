<?php
namespace Test\Feature\TRegx\CleanRegex\Match\offsets;

use PHPUnit\Framework\TestCase;
use Test\Utils\CustomSubjectException;
use Test\Utils\Functions;
use TRegx\CleanRegex\Exception\NoSuchElementFluentException;
use TRegx\CleanRegex\Exception\NoSuchNthElementException;
use TRegx\CleanRegex\Exception\SubjectNotMatchedException;
use TRegx\CleanRegex\Match\Details\NotMatched;
use function pattern;

/**
 * @coversNothing
 */
class AbstractMatchPatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldThrow_first_OnUnmatchedSubject()
    {
        // then
        $this->expectException(SubjectNotMatchedException::class);
        $this->expectExceptionMessage("Expected to get the first match offset, but subject was not matched");

        // when
        pattern('Foo')->match('Bar')->offsets()->first();
    }

    /**
     * @test
     */
    public function shouldThrow_nth_OnUnmatchedSubject()
    {
        // then
        $this->expectException(SubjectNotMatchedException::class);
        $this->expectExceptionMessage("Expected to get the 0-nth match offset, but subject was not matched");

        // when
        pattern('Foo')->match('Bar')->offsets()->nth(0);
    }

    /**
     * @test
     */
    public function shouldThrow_map_first_OnUnmatchedSubject()
    {
        // then
        $this->expectException(NoSuchElementFluentException::class);
        $this->expectExceptionMessage("Expected to get the first element from fluent pattern, but the subject backing the feed was not matched");

        // when
        pattern('Foo')->match('Bar')->offsets()->map(Functions::fail())->first();
    }

    /**
     * @test
     */
    public function shouldThrow_map_nth_OnUnmatchedSubject()
    {
        // then
        $this->expectException(NoSuchElementFluentException::class);
        $this->expectExceptionMessage("Expected to get the 0-nth element from fluent pattern, but the subject backing the feed was not matched");

        // when
        pattern('Foo')->match('Bar')->offsets()->map(Functions::fail())->nth(0);
    }

    /**
     * @test
     */
    public function shouldThrow_filter_first()
    {
        // then
        $this->expectException(NoSuchElementFluentException::class);
        $this->expectExceptionMessage("Expected to get the first element from fluent pattern, but the elements feed has 0 element(s)");

        // when
        pattern('\d+')->match('12')->offsets()->filter(Functions::constant(false))->first();
    }

    /**
     * @test
     */
    public function shouldThrow_filter_nth()
    {
        // then
        $this->expectException(NoSuchElementFluentException::class);
        $this->expectExceptionMessage("Expected to get the 2-nth element from fluent pattern, but the elements feed has 0 element(s)");

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
        $this->expectExceptionMessage('Expected to get the 2-nth match offset, but only 2 occurrences were matched');

        // when
        pattern('\d+')->match('23 25')->offsets()->nth(2);
    }

    /**
     * @test
     */
    public function shouldThrow_findFirst_orThrow_WithCustomException()
    {
        try {
            // when
            pattern('Foo')->match('Bar')->offsets()->findFirst(Functions::fail())->orThrow(CustomSubjectException::class);
        } catch (CustomSubjectException $exception) {
            // then
            $this->assertSame('Bar', $exception->subject);
        }
    }

    /**
     * @test
     */
    public function shouldThrow_findNth_orThrow_WithCustomException()
    {
        try {
            // when
            pattern('\d+')->match('12 13 14')->offsets()->findNth(5)->orThrow(CustomSubjectException::class);
        } catch (CustomSubjectException $exception) {
            // then
            $this->assertSame('12 13 14', $exception->subject);
        }
    }

    /**
     * @test
     */
    public function shouldThrow_offsets_findFirst_OnUnmatchedPattern_orElse()
    {
        // given
        pattern('(?<sparrow>Foo)')->match('Bar')->offsets()->findFirst(Functions::fail())
            ->orElse(function (NotMatched $notMatched) {
                $this->assertSame(['sparrow'], $notMatched->groupNames());
            });
    }

    /**
     * @test
     */
    public function shouldThrow_offsets_findNth_OnUnmatchedPattern_orElse()
    {
        // given
        pattern('(?<sparrow>Foo)')->match('Bar')->offsets()->findNth(0)
            ->orElse(function (NotMatched $notMatched) {
                $this->assertSame(['sparrow'], $notMatched->groupNames());
            });
    }

    /**
     * @test
     */
    public function shouldThrow_offsets_findNth_OnInfussificientMatch_orElse()
    {
        // given
        pattern('(?<sparrow>Foo)')->match('Foo')->offsets()->findNth(1)
            ->orElse(function (NotMatched $notMatched) {
                $this->assertSame(['sparrow'], $notMatched->groupNames());
            });
    }
}

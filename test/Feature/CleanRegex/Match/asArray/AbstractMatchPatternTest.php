<?php
namespace Test\Feature\TRegx\CleanRegex\Match\asArray;

use PHPUnit\Framework\TestCase;
use Test\Utils\CustomSubjectException;
use Test\Utils\Functions;
use TRegx\CleanRegex\Exception\NoSuchElementFluentException;
use TRegx\CleanRegex\Exception\NoSuchNthElementException;
use TRegx\CleanRegex\Exception\SubjectNotMatchedException;

/**
 * @coversNothing
 */
class AbstractMatchPatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldThrow_asArray_first_OnUnmatchedSubject()
    {
        // then
        $this->expectException(SubjectNotMatchedException::class);
        $this->expectExceptionMessage("Expected to get the first match as array, but subject was not matched");

        // when
        pattern('Foo')->match('Bar')->asArray()->first();
    }

    /**
     * @test
     */
    public function shouldThrow_asArray_nth_OnUnmatchedSubject()
    {
        // then
        $this->expectException(SubjectNotMatchedException::class);
        $this->expectExceptionMessage("Expected to get the 0-nth match as array, but subject was not matched");

        // when
        pattern('Foo')->match('Bar')->asArray()->nth(0);
    }

    /**
     * @test
     */
    public function shouldThrow_asArray_map_first_OnUnmatchedSubject()
    {
        // then
        $this->expectException(NoSuchElementFluentException::class);
        $this->expectExceptionMessage("Expected to get the first element from fluent pattern, but the subject backing the feed was not matched");

        // when
        pattern('Foo')->match('Bar')->asArray()->map(Functions::fail())->first();
    }

    /**
     * @test
     */
    public function shouldThrow_asArray_map_nth_OnUnmatchedSubject()
    {
        // then
        $this->expectException(NoSuchElementFluentException::class);
        $this->expectExceptionMessage("Expected to get the 0-nth element from fluent pattern, but the subject backing the feed was not matched");

        // when
        pattern('Foo')->match('Bar')->asArray()->map(Functions::fail())->nth(0);
    }

    /**
     * @test
     */
    public function shouldThrow_asArray_filter_first()
    {
        // then
        $this->expectException(NoSuchElementFluentException::class);
        $this->expectExceptionMessage("Expected to get the first element from fluent pattern, but the elements feed has 0 element(s)");

        // when
        pattern('Foo')->match('Foo')->asArray()->filter(Functions::constant(false))->first();
    }

    /**
     * @test
     */
    public function shouldThrow_asArray_filter_nth()
    {
        // then
        $this->expectException(NoSuchElementFluentException::class);
        $this->expectExceptionMessage("Expected to get the 2-nth element from fluent pattern, but the elements feed has 0 element(s)");

        // when
        pattern('Foo')->match('Foo Foo')->asArray()->filter(Functions::constant(false))->nth(2);
    }

    /**
     * @test
     */
    public function shouldThrow_asArray_nth()
    {
        // then
        $this->expectException(NoSuchNthElementException::class);
        $this->expectExceptionMessage("Expected to get the 2-nth match as array, but the elements feed has 2 element(s)");

        // when
        pattern('Foo')->match('Foo Foo')->asArray()->nth(2);
    }

    /**
     * @test
     */
    public function shouldThrow_asArray_findFirst_orThrow_WithCustomException()
    {
        try {
            // when
            pattern('Foo')->match('Bar')->asArray()->findFirst(Functions::fail())->orThrow(CustomSubjectException::class);
        } catch (CustomSubjectException $exception) {
            // then
            $this->assertSame('Bar', $exception->subject);
        }
    }

    /**
     * @test
     */
    public function shouldThrow_asArray_findNth_orThrow_WithCustomException()
    {
        try {
            // when
            pattern('Foo')->match('Foo Foo')->asArray()->findNth(4)->orThrow(CustomSubjectException::class);
        } catch (CustomSubjectException $exception) {
            // then
            $this->assertSame('Foo Foo', $exception->subject);
        }
    }
}

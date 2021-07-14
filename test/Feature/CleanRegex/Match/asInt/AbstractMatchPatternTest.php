<?php
namespace Test\Feature\TRegx\CleanRegex\Match\asInt;

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
    public function shouldThrow_asInt_first_OnUnmatchedSubject()
    {
        // then
        $this->expectException(SubjectNotMatchedException::class);
        $this->expectExceptionMessage("Expected to get the first match as integer, but subject was not matched");

        // when
        pattern('Foo')->match('Bar')->asInt()->first();
    }

    /**
     * @test
     */
    public function shouldThrow_asInt_nth_OnUnmatchedSubject()
    {
        // then
        $this->expectException(SubjectNotMatchedException::class);
        $this->expectExceptionMessage("Expected to get the 0-nth match as integer, but subject was not matched");

        // when
        pattern('Foo')->match('Bar')->asInt()->nth(0);
    }

    /**
     * @test
     */
    public function shouldThrow_asInt_map_first_OnUnmatchedSubject()
    {
        // then
        $this->expectException(NoSuchElementFluentException::class);
        $this->expectExceptionMessage("Expected to get the first element from fluent pattern, but the subject backing the feed was not matched");

        // when
        pattern('Foo')->match('Bar')->asInt()->map(Functions::fail())->first();
    }

    /**
     * @test
     */
    public function shouldThrow_asInt_map_nth_OnUnmatchedSubject()
    {
        // then
        $this->expectException(NoSuchElementFluentException::class);
        $this->expectExceptionMessage("Expected to get the 0-nth element from fluent pattern, but the subject backing the feed was not matched");

        // when
        pattern('Foo')->match('Bar')->asInt()->map(Functions::fail())->nth(0);
    }

    /**
     * @test
     */
    public function shouldThrow_asInt_filter_first()
    {
        // then
        $this->expectException(NoSuchElementFluentException::class);
        $this->expectExceptionMessage("Expected to get the first element from fluent pattern, but the elements feed has 0 element(s)");

        // when
        pattern('\d+')->match('12')->asInt()->filter(Functions::constant(false))->first();
    }

    /**
     * @test
     */
    public function shouldThrow_asInt_filter_nth()
    {
        // then
        $this->expectException(NoSuchElementFluentException::class);
        $this->expectExceptionMessage("Expected to get the 2-nth element from fluent pattern, but the elements feed has 0 element(s)");

        // when
        pattern('\d+')->match('13 14')->asInt()->filter(Functions::constant(false))->nth(2);
    }

    /**
     * @test
     */
    public function shouldThrow_asInt_nth()
    {
        // then
        $this->expectException(NoSuchNthElementException::class);
        $this->expectExceptionMessage("Expected to get the 2-nth match as integer, but the elements feed has 2 element(s)");

        // when
        pattern('\d+')->match('23 25')->asInt()->nth(2);
    }

    /**
     * @test
     */
    public function shouldThrow_asInt_findFirst_orThrow_WithCustomException()
    {
        try {
            // when
            pattern('Foo')->match('Bar')->asInt()->findFirst(Functions::fail())->orThrow(CustomSubjectException::class);
        } catch (CustomSubjectException $exception) {
            // then
            $this->assertSame('Bar', $exception->subject);
        }
    }

    /**
     * @test
     */
    public function shouldThrow_asInt_findNth_orThrow_WithCustomException()
    {
        try {
            // when
            pattern('\d+')->match('12 13 14')->asInt()->findNth(5)->orThrow(CustomSubjectException::class);
        } catch (CustomSubjectException $exception) {
            // then
            $this->assertSame('12 13 14', $exception->subject);
        }
    }
}

<?php
namespace Test\Feature\TRegx\CleanRegex\Match\asInt\keys;

use PHPUnit\Framework\TestCase;
use Test\Utils\CustomSubjectException;
use Test\Utils\Functions;
use TRegx\CleanRegex\Exception\NoSuchStreamElementException;
use function pattern;

class AbstractMatchPatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldThrow_first_OnUnmatchedSubject()
    {
        // then
        $this->expectException(NoSuchStreamElementException::class);
        $this->expectExceptionMessage('Expected to get the first match as integer, but subject was not matched');

        // when
        pattern('Foo')->match('Bar')->asInt()->keys()->first();
    }

    /**
     * @test
     */
    public function shouldThrow_nth_OnUnmatchedSubject()
    {
        // then
        $this->expectException(NoSuchStreamElementException::class);
        $this->expectExceptionMessage('Expected to get the 0-nth stream element, but the subject backing the stream was not matched');

        // when
        pattern('Foo')->match('Bar')->asInt()->keys()->nth(0);
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
        pattern('\d+')->match('12')->asInt()->filter(Functions::constant(false))->keys()->first();
    }

    /**
     * @test
     */
    public function shouldThrow_filter_nth()
    {
        // then
        $this->expectException(NoSuchStreamElementException::class);
        $this->expectExceptionMessage("Expected to get the 2-nth stream element, but the stream has 0 element(s)");

        // when
        pattern('\d+')->match('13 14')->asInt()->filter(Functions::constant(false))->keys()->nth(2);
    }

    /**
     * @test
     */
    public function shouldThrow_nth()
    {
        // then
        $this->expectException(NoSuchStreamElementException::class);
        $this->expectExceptionMessage("Expected to get the 2-nth stream element, but the stream has 2 element(s)");

        // when
        pattern('\d+')->match('23 25')->asInt()->keys()->nth(2);
    }

    /**
     * @test
     */
    public function shouldThrow_findFirst_orThrow_WithCustomException()
    {
        // then
        $this->expectException(CustomSubjectException::class);
        $this->expectExceptionMessage('Expected to get the first match as integer, but subject was not matched');

        // when
        pattern('Foo')->match('Bar')->asInt()->keys()->findFirst(Functions::fail())->orThrow(CustomSubjectException::class);
    }

    /**
     * @test
     */
    public function shouldThrow_findNth_orThrow_WithCustomException()
    {
        // then
        $this->expectException(CustomSubjectException::class);
        $this->expectExceptionMessage('Expected to get the 5-nth stream element, but the stream has 3 element(s)');

        // when
        pattern('\d+')->match('12 13 14')->asInt()->keys()->findNth(5)->orThrow(CustomSubjectException::class);
    }

    /**
     * @test
     */
    public function shouldThrow_keys_keys_first_OnUnmatchedSubject()
    {
        // then
        $this->expectException(NoSuchStreamElementException::class);
        $this->expectExceptionMessage('Expected to get the first match as integer, but subject was not matched');

        // when
        pattern('Foo')->match('Bar')->asInt()->keys()->keys()->first();
    }
}

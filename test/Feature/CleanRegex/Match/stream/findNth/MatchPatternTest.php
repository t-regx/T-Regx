<?php
namespace Test\Feature\CleanRegex\Match\stream\findNth;

use PHPUnit\Framework\TestCase;
use Test\Utils\ExactExceptionMessage;
use Test\Utils\ExampleException;
use Test\Utils\Functions;
use TRegx\CleanRegex\Exception\NoSuchNthElementException;

/**
 * @covers \TRegx\CleanRegex\Match\MatchPattern
 * @covers \TRegx\CleanRegex\Match\Stream
 */
class MatchPatternTest extends TestCase
{
    use ExactExceptionMessage;

    /**
     * @test
     */
    public function shouldThrow_findNth_forUnmatchedSubject()
    {
        // given
        $optional = pattern('Foo')->match('Bar')->stream()->findNth(5);
        // then
        $this->expectException(NoSuchNthElementException::class);
        $this->expectExceptionMessage('Expected to get the 5-nth stream element, but the subject backing the stream was not matched');
        // when
        $optional->get();
    }

    /**
     * @test
     */
    public function shouldThrow_findNth_forStreamUnderflow()
    {
        // given
        $optional = pattern('\d+')->match('12 13 14')->stream()->findNth(5);
        // then
        $this->expectException(NoSuchNthElementException::class);
        $this->expectExceptionMessage('Expected to get the 5-nth stream element, but the stream has 3 element(s)');
        // when
        $optional->get();
    }

    /**
     * @test
     */
    public function shouldThrow_findNth_forEmptyStream()
    {
        // given
        $optional = pattern('Foo')->match('Foo')->stream()->filter(Functions::constant(false))->findNth(5);
        // then
        $this->expectException(NoSuchNthElementException::class);
        $this->expectExceptionMessage('Expected to get the 5-nth stream element, but the stream has 0 element(s)');
        // when
        $optional->get();
    }

    /**
     * @test
     */
    public function shouldThrow_findNth_orThrow_WithCustomException()
    {
        // then
        $this->expectException(ExampleException::class);
        // when
        pattern('\d+')->match('12 13 14')->stream()->findNth(5)->orThrow(new ExampleException());
    }

    /**
     * @test
     */
    public function shouldCall_findNth_forUnmatchedSubject()
    {
        // when
        pattern('(?<pepsi>Foo)')->match('Bar')->stream()->findNth(0)->orElse(Functions::pass());
    }

    /**
     * @test
     */
    public function shouldCall_findNth_forStreamUnderflow()
    {
        // given
        pattern('(?<pepsi>\d+)')->match('Foo 14')->stream()->findNth(1)->orElse(Functions::pass());
    }
}

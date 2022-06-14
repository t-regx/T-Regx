<?php
namespace Test\Feature\CleanRegex\Match\asInt;

use PHPUnit\Framework\TestCase;
use Test\Utils\Classes\ExampleException;
use Test\Utils\Functions;
use Test\Utils\TestCase\TestCaseExactMessage;
use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\CleanRegex\Exception\IntegerFormatException;
use TRegx\CleanRegex\Exception\IntegerOverflowException;
use TRegx\CleanRegex\Exception\NoSuchNthElementException;
use TRegx\CleanRegex\Exception\NoSuchStreamElementException;
use TRegx\CleanRegex\Exception\SubjectNotMatchedException;
use TRegx\CleanRegex\Pattern;

/**
 * @covers \TRegx\CleanRegex\Match\MatchPattern
 * @covers \TRegx\CleanRegex\Match\Stream
 */
class MatchPatternTest extends TestCase
{
    use TestCaseExactMessage;

    /**
     * @test
     */
    public function shouldGetIntegerFirst()
    {
        // when
        $integer = pattern('\d+')->match('123')->asInt(10)->first();
        // then
        $this->assertSame(123, $integer);
    }

    /**
     * @test
     */
    public function shouldGetFirstInBase2()
    {
        // given
        $stream = Pattern::of('\d+')->match('10101')->asInt(2);
        // when
        $first = $stream->first();
        // then
        $this->assertSame(21, $first);
    }

    /**
     * @test
     */
    public function shouldGetFirstLowerBound()
    {
        // given
        $stream = Pattern::of('-\d+')->match('-2147483648')->asInt();
        // when
        $first = $stream->first();
        // then
        $this->assertSame(-2147483647 - 1, $first); // for some reason, PHP parses -2147483648 as float ;|
    }

    /**
     * @test
     */
    public function shouldGetFirstKey()
    {
        // given
        $stream = Pattern::of('\d+')->match('192')->asInt();
        // when
        $key = $stream->keys()->first();
        // then
        $this->assertSame(0, $key);
    }

    /**
     * @test
     */
    public function shouldInvokeConsumerForFirstAsInteger()
    {
        // given
        $pattern = Pattern::of('\d+')->match('Foo 1 Bar 34 Lorem 42 Ipsum');
        // when
        $pattern->asInt()->first(Functions::assertSame(1));
    }

    /**
     * @test
     */
    public function shouldGetAllAsInt()
    {
        // given
        $stream = Pattern::of('\d+')->match('14, 19, 25')->asInt();
        // when
        $all = $stream->all();
        // then
        $this->assertSame([14, 19, 25], $all);
    }

    /**
     * @test
     */
    public function shouldGetEmptyForUnmatchedSubject()
    {
        // given
        $stream = Pattern::of('Faramir')->match('I love you, Boromir')->asInt();
        // when
        $all = $stream->all();
        // then
        $this->assertSame([], $all);
    }

    /**
     * @test
     */
    public function shouldThrow_first_OnUnmatchedSubject()
    {
        // then
        $this->expectException(SubjectNotMatchedException::class);
        $this->expectExceptionMessage('Expected to get the first match as integer, but subject was not matched');
        // when
        pattern('Foo')->match('Bar')->asInt()->first();
    }

    /**
     * @test
     */
    public function shouldThrow_nth_OnUnmatchedSubject()
    {
        // then
        $this->expectException(NoSuchNthElementException::class);
        $this->expectExceptionMessage('Expected to get the 0-nth match as integer, but subject was not matched');
        // when
        pattern('Foo')->match('Bar')->asInt()->nth(0);
    }

    /**
     * @test
     */
    public function shouldThrow_map_first_OnUnmatchedSubject()
    {
        // then
        $this->expectException(NoSuchStreamElementException::class);
        $this->expectExceptionMessage('Expected to get the first match as integer, but subject was not matched');
        // when
        pattern('Foo')->match('Bar')->asInt()->map(Functions::fail())->first();
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
        pattern('Foo')->match('Bar')->asInt()->map(Functions::fail())->nth(0);
    }

    /**
     * @test
     */
    public function shouldThrow_filter_first()
    {
        // then
        $this->expectException(NoSuchStreamElementException::class);
        $this->expectExceptionMessage("Expected to get the first stream element, but the stream has 0 element(s)");
        // when
        pattern('\d+')->match('12')->asInt()->filter(Functions::constant(false))->first();
    }

    /**
     * @test
     */
    public function shouldThrow_filter_nth()
    {
        // then
        $this->expectException(NoSuchNthElementException::class);
        $this->expectExceptionMessage("Expected to get the 2-nth stream element, but the stream has 0 element(s)");

        // when
        pattern('\d+')->match('13 14')->asInt()->filter(Functions::constant(false))->nth(2);
    }

    /**
     * @test
     */
    public function shouldThrow_nth()
    {
        // then
        $this->expectException(NoSuchNthElementException::class);
        $this->expectExceptionMessage("Expected to get the 2-nth match as integer, but only 2 occurrences are available");

        // when
        pattern('\d+')->match('23 25')->asInt()->nth(2);
    }

    /**
     * @test
     */
    public function shouldThrow_findFirst_orThrow_WithCustomException()
    {
        // then
        $this->expectException(ExampleException::class);
        // when
        pattern('Foo')->match('Bar')->asInt()->findFirst(Functions::fail())->orThrow(new ExampleException());
    }

    /**
     * @test
     */
    public function shouldThrow_findNth_orThrow_WithCustomException()
    {
        // then
        $this->expectException(ExampleException::class);
        // when
        pattern('\d+')->match('12 13 14')->asInt()->findNth(5)->orThrow(new ExampleException());
    }

    /**
     * @test
     */
    public function shouldCall_asInt_findFirst_OnUnmatchedPattern_orElse()
    {
        // when
        pattern('Foo')->match('Bar')->asInt()->findFirst(Functions::fail())->orElse(Functions::pass());
    }

    /**
     * @test
     */
    public function shouldReturn_asInt_findFirst_OnUnmatchedPattern_orReturn()
    {
        // when
        $value = pattern('Foo')->match('Bar')->asInt()->findFirst(Functions::fail())->orReturn('value');

        // then
        $this->assertSame('value', $value);
    }

    /**
     * @test
     */
    public function shouldCall_asInt_findFirst_OnUnmatchedPattern_mapped_orElse()
    {
        // when
        pattern('Foo')->match('Bar')->asInt()->findFirst(Functions::fail())->map(Functions::fail())->orElse(Functions::pass());
    }

    /**
     * @test
     */
    public function shouldCall_asInt_findNth_OnUnmatchedPattern_orElse()
    {
        // when
        pattern('(?<pepsi>Foo)')->match('Bar')->asInt()->findNth(0)->orElse(Functions::pass());
    }

    /**
     * @test
     */
    public function shouldCall_asInt_findNth_OnInsufficientMatch_orElse()
    {
        // given
        pattern('(?<pepsi>\d+)')->match('Foo 14')->asInt()->findNth(1)->orElse(Functions::pass());
    }

    /**
     * @test
     */
    public function shouldGroupByCallback()
    {
        // when
        $grouppedBy = pattern('\d+')->match('192.127.0.1')
            ->asInt()
            ->groupByCallback(Functions::mod('even', 'odd'))
            ->all();

        // then
        $this->assertSame(['even' => [192, 0], 'odd' => [127, 1]], $grouppedBy);
    }

    /**
     * @test
     */
    public function shouldPassThrough_first()
    {
        // given
        $throwable = new GroupNotMatchedException('message');
        // then
        $this->expectException(GroupNotMatchedException::class);
        // when
        pattern('(12)')->match('12')->asInt()->first(Functions::throws($throwable));
    }

    /**
     * @test
     */
    public function shouldThrowExceptionAsIntStream()
    {
        // then
        $this->expectException(SubjectNotMatchedException::class);
        // when
        pattern('\d+')->match('Foo')->asInt()->first();
    }

    /**
     * @test
     */
    public function shouldThrowExceptionAsStream()
    {
        // then
        $this->expectException(NoSuchStreamElementException::class);
        // when
        pattern('\d+')->match('Foo')->asInt()->stream()->first();
    }

    /**
     * @test
     */
    public function shouldThrow_all_forMalformedInteger()
    {
        // given
        $stream = Pattern::of('\w+')->match('Foo, Bar')->asInt();
        // then
        $this->expectException(IntegerFormatException::class);
        $this->expectExceptionMessage("Expected to parse 'Foo', but it is not a valid integer in base 10");
        // when
        $stream->all();
    }

    /**
     * @test
     */
    public function shouldThrow_first_forMalformedInteger()
    {
        // given
        $stream = Pattern::of('\w+')->match('Foo, Bar')->asInt(11);
        // then
        $this->expectException(IntegerFormatException::class);
        $this->expectExceptionMessage("Expected to parse 'Foo', but it is not a valid integer in base 11");
        // when
        $stream->first();
    }

    /**
     * @test
     */
    public function shouldThrow_firstKey_forMalformedInteger()
    {
        // given
        $stream = Pattern::of('Foo')->match('Foo')->asInt(13);
        // then
        $this->expectException(IntegerFormatException::class);
        $this->expectExceptionMessage("Expected to parse 'Foo', but it is not a valid integer in base 13");
        // when
        $stream->keys()->first();
    }

    /**
     * @test
     */
    public function shouldThrow_firstKey_forOverflownInteger()
    {
        // given
        $stream = Pattern::of('\d+')->match('922337203685477580700')->asInt(13);
        // then
        $this->expectException(IntegerOverflowException::class);
        $this->expectExceptionMessage("Expected to parse '922337203685477580700', but it exceeds integer size on this architecture in base 13");
        // when
        $stream->keys()->first();
    }

    /**
     * @test
     */
    public function shouldBeCountable()
    {
        // given
        $stream = pattern('\d+')->match('1, 2, 3, 4')->asInt();
        // when
        $count = \count($stream);
        // then
        $this->assertSame(4, $count);
    }
}

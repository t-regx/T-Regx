<?php
namespace Test\Feature\TRegx\CleanRegex\Replace\_implicit_all;

use PHPUnit\Framework\TestCase;
use Test\Utils\CustomSubjectException;
use Test\Utils\Functions;
use TRegx\CleanRegex\Exception\InvalidReplacementException;
use TRegx\CleanRegex\Exception\NotReplacedException;
use TRegx\CleanRegex\Match\Details\Detail;

class ReplaceLimitTest extends TestCase
{
    /**
     * @test
     */
    public function shouldReplace_with()
    {
        // when
        $result = pattern('Foo')->replace('Subject:Foo Foo Foo')->with('Bar');

        // then
        $this->assertSame('Subject:Bar Bar Bar', $result);
    }

    /**
     * @test
     */
    public function shouldReplace_withReferences()
    {
        // when
        $result = pattern('Foo:(\d+)')->replace('Foo:3 Foo:4')->withReferences('X:"$1"');

        // then
        $this->assertSame('X:"3" X:"4"', $result);
    }

    /**
     * @test
     */
    public function shouldReplace_callback()
    {
        // when
        $result = pattern('\w+')->replace('Welcome home')->callback(function (Detail $detail) {
            return "'{$detail->subject()}'";
        });

        // then
        $this->assertSame("'Welcome home' 'Welcome home'", $result);
    }

    /**
     * @test
     */
    public function shouldThrow_callback_ForInvalidReturn()
    {
        // then
        $this->expectException(InvalidReplacementException::class);
        $this->expectExceptionMessage('Invalid callback() callback return type. Expected string, but integer (12) given');

        // when
        pattern('\w+')->replace('Foo:car Foo:bar')->callback(Functions::constant(12));
    }

    /**
     * @test
     */
    public function shouldReplace_by()
    {
        // when
        $result = pattern('\w+')->replace('A, O, A')->by()->map(['A' => 'apple', 'O' => 'orange']);

        // then
        $this->assertSame('apple, orange, apple', $result);
    }

    /**
     * @test
     */
    public function shouldReplace_otherwiseThrowing()
    {
        // when
        $result = pattern('\w+')->replace('Foo, Bar, Cat')->otherwiseThrowing()->with('X');

        // then
        $this->assertSame('X, X, X', $result);
    }

    /**
     * @test
     */
    public function shouldThrow_otherwiseThrowing()
    {
        // then
        $this->expectException(NotReplacedException::class);
        $this->expectExceptionMessage("Replacements were supposed to be performed, but subject doesn't match the pattern");

        // when
        pattern('Foo')->replace('Bar')->otherwiseThrowing()->with('X');
    }

    /**
     * @test
     */
    public function shouldThrow_otherwiseThrowing_WithCustomException()
    {
        // then
        $this->expectException(CustomSubjectException::class);
        $this->expectExceptionMessage("Replacements were supposed to be performed, but subject doesn't match the pattern");

        // when
        pattern('Foo')->replace('Bar')->otherwiseThrowing(CustomSubjectException::class)->with('X');
    }

    /**
     * @test
     */
    public function shouldReplace_otherwiseReturning()
    {
        // when
        $result = pattern('Foo')->replace('Foo Foo Foo')->otherwiseReturning('Otherwise')->with('Bar');

        // then
        $this->assertSame('Bar Bar Bar', $result);
    }

    /**
     * @test
     */
    public function shouldReturn_otherwiseReturning_OnUnmatchedSubject()
    {
        // when
        $result = pattern('Foo')->replace('Bar')->otherwiseReturning('Otherwise')->with('Bar');

        // then
        $this->assertSame('Otherwise', $result);
    }

    /**
     * @test
     */
    public function shouldReplace_otherwise()
    {
        // when
        $result = pattern('Foo')->replace('Foo Foo Foo')->otherwise(Functions::fail())->with('Bar');

        // then
        $this->assertSame('Bar Bar Bar', $result);
    }

    /**
     * @test
     */
    public function shouldReturn_otherwise_OnUnmatchedSubject()
    {
        // when
        $result = pattern('Foo')->replace('Bar')->otherwise(Functions::constant('Otherwise'))->with('Bar');

        // then
        $this->assertSame('Otherwise', $result);
    }

    /**
     * @test
     */
    public function shouldCall_otherwise_OnUnmatchedSubject()
    {
        // when
        pattern('Foo')->replace('subject')
            ->otherwise(function ($subject) {
                // then
                $this->assertSame('subject', $subject);

                // cleanup
                return "";
            })
            ->with('Bar');
    }

    /**
     * @test
     */
    public function shouldCall_counting()
    {
        // when
        $result = pattern('\d+')
            ->replace('14 19 12 21')
            ->counting(function ($count) {
                // then
                $this->assertSame(4, $count);

                // cleanup
                return "";
            })
            ->with('Bar');

        // then
        $this->assertSame('Bar Bar Bar Bar', $result);
    }

    /**
     * @test
     */
    public function shouldCall_counting_OnUnmatchedSubject()
    {
        // when
        pattern('Foo')->replace('subject')
            ->counting(function ($count) {
                // then
                $this->assertSame(0, $count);

                // cleanup
                return "";
            })
            ->with('Bar');
    }

    /**
     * @test
     */
    public function shouldReplace_focus()
    {
        // when
        $result = pattern('(&)(?<name>\w+)')->replace('&Foo &Bar &Cat')->focus('name')->with('X');

        // then
        $this->assertSame("&X &X &X", $result);
    }
}

<?php
namespace Test\Feature\CleanRegex\Replace\_implicit_all;

use PHPUnit\Framework\TestCase;
use Test\Utils\Functions;
use Test\Utils\TestCase\TestCaseExactMessage;
use TRegx\CleanRegex\Exception\InvalidReplacementException;
use TRegx\CleanRegex\Match\Detail;

class Test extends TestCase
{
    use TestCaseExactMessage;

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

}

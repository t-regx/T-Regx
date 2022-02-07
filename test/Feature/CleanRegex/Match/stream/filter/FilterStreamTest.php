<?php
namespace Test\Feature\TRegx\CleanRegex\Match\stream\filter;

use PHPUnit\Framework\TestCase;
use Test\Utils\DetailFunctions;
use Test\Utils\Functions;

class FilterStreamTest extends TestCase
{
    /**
     * @test
     */
    public function shouldReturn_filter_nth()
    {
        // when
        $result = pattern('\w+')->match('Lorem ipsum dolor')->stream()->filter(DetailFunctions::notEquals('Lorem'))->nth(1);

        // then
        $this->assertSame('dolor', $result->text());
    }

    /**
     * @test
     */
    public function shouldNotCall_first_OnUnmatchedSubject()
    {
        // when
        pattern('Foo')->match('Bar')->stream()
            ->filter(Functions::fail())
            ->findFirst(Functions::fail())
            ->orElse(Functions::pass());
    }

    /**
     * @test
     */
    public function shouldReturn_first_FirstMatch()
    {
        // when
        $result = pattern('\w+')->match('Foo, Bar')->stream()->filter(DetailFunctions::equals('Foo'))->first();

        // then
        $this->assertSame('Foo', $result->text());
    }

    /**
     * @test
     */
    public function shouldReturn_first_NotFirstMatch()
    {
        // when
        $result = pattern('\w+')->match('Foo, Bar, Dor')->stream()->filter(DetailFunctions::equals('Dor'))->first();

        // then
        $this->assertSame('Dor', $result->text());
    }

    /**
     * @test
     */
    public function shouldReturn_keys_first_FirstMatch()
    {
        // when
        $key = pattern('\w+')->match('Foo, Bar')->stream()->filter(DetailFunctions::equals('Foo'))->keys()->first();

        // then
        $this->assertSame(0, $key);
    }

    /**
     * @test
     */
    public function shouldReturn_keys_first_NotFirstMatch()
    {
        // when
        $key = pattern('\w+')->match('Foo, Bar, Dor')->stream()->filter(DetailFunctions::equals('Dor'))->keys()->first();

        // then
        $this->assertSame(0, $key);
    }

    /**
     * @test
     */
    public function shouldNotCall_TwiceForTheSameDetail()
    {
        // when
        pattern('\w+')->match('Foo, Bar, Dor, Ver, Sir')
            ->stream()
            ->filter(DetailFunctions::collecting($calls, DetailFunctions::equals('Sir')))
            ->first();

        // then
        $this->assertSame(['Foo', 'Bar', 'Dor', 'Ver', 'Sir'], $calls);
    }

    /**
     * @test
     */
    public function shouldFilter_first_untilFound()
    {
        // given
        $invokedFor = [];

        // when
        $first = pattern('(one|two|three|four|five|six)')
            ->match('one two three four five six')
            ->stream()
            ->filter(function (string $text) use (&$invokedFor) {
                $invokedFor[] = $text;
                return $text === 'four';
            })
            ->map(DetailFunctions::text())
            ->first();

        // then
        $this->assertSame('four', $first);
        $this->assertSame(['one', 'two', 'three', 'four'], $invokedFor);
    }

    /**
     * @test
     */
    public function shouldBe_Countable()
    {
        // given
        $pattern = pattern('\w+')->match('One, two, three')->stream()->filter(DetailFunctions::notEquals('two'));
        $this->assertIsNotArray($pattern);

        // when
        $size = count($pattern);

        // then
        $this->assertSame(2, $size);
    }
}

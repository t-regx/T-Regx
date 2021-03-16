<?php
namespace Test\Feature\TRegx\CleanRegex\Match\fluent\filter;

use PHPUnit\Framework\TestCase;
use Test\Utils\Functions;

class FilterStreamTest extends TestCase
{
    /**
     * @test
     */
    public function shouldReturn_filter_nth()
    {
        // when
        $result = pattern('\w+')->match('Lorem ipsum dolor')->fluent()->filter(Functions::notEquals('Lorem'))->nth(1);

        // then
        $this->assertSame('dolor', $result->text());
    }

    /**
     * @test
     */
    public function shouldNotCall_first_OnUnmatchedSubject()
    {
        // when
        pattern('Foo')->match('Bar')->fluent()
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
        $result = pattern('\w+')->match('Foo, Bar')->fluent()->filter(Functions::equals('Foo'))->first();

        // then
        $this->assertSame('Foo', $result->text());
    }

    /**
     * @test
     */
    public function shouldReturn_first_NotFirstMatch()
    {
        // when
        $result = pattern('\w+')->match('Foo, Bar, Dor')->fluent()->filter(Functions::equals('Dor'))->first();

        // then
        $this->assertSame('Dor', $result->text());
    }

    /**
     * @test
     */
    public function shouldReturn_keys_first_FirstMatch()
    {
        // when
        $key = pattern('\w+')->match('Foo, Bar')->fluent()->filter(Functions::equals('Foo'))->keys()->first();

        // then
        $this->assertSame(0, $key);
    }

    /**
     * @test
     */
    public function shouldReturn_keys_first_NotFirstMatch()
    {
        // when
        $key = pattern('\w+')->match('Foo, Bar, Dor')->fluent()->filter(Functions::equals('Dor'))->keys()->first();

        // then
        $this->assertSame(2, $key);
    }

    /**
     * @test
     */
    public function shouldNotCall_TwiceForTheSameDetail()
    {
        // when
        pattern('\w+')->match('Foo, Bar, Dor, Ver, Sir')
            ->fluent()
            ->filter(Functions::collecting($calls, Functions::equals('Sir')))
            ->first();

        // then
        $this->assertSame(['Foo', 'Bar', 'Dor', 'Ver', 'Sir'], $calls);
    }
}

<?php
namespace Test\Feature\TRegx\CleanRegex\Match\remaining\stream\filter;

use PHPUnit\Framework\TestCase;
use Test\Utils\DetailFunctions;
use Test\Utils\Functions;

class FilterStreamTest extends TestCase
{
    /**
     * @test
     */
    public function shouldReturn_nth()
    {
        // when
        $nth = pattern('\w+')->match('Lorem ipsum dolor sit amet')
            ->remaining(DetailFunctions::notEquals('Lorem'))
            ->stream()
            ->filter(DetailFunctions::notEquals('ipsum'))
            ->nth(2);

        // then
        $this->assertSame('amet', $nth->text());
    }

    /**
     * @test
     */
    public function shouldNotCall_first_OnUnmatchedSubject()
    {
        // when
        pattern('Foo')->match('Bar')
            ->remaining(Functions::fail())
            ->stream()
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
        $result = pattern('\w+')->match('Foo, Bar, Dor')
            ->remaining(DetailFunctions::notEquals('Foo'))
            ->stream()
            ->filter(DetailFunctions::equals('Bar'))
            ->first();

        // then
        $this->assertSame('Bar', $result->text());
    }

    /**
     * @test
     */
    public function shouldReturn_first_NotFirstMatch()
    {
        // when
        $result = pattern('\w+')->match('Foo, Bar, Dor, Sir')
            ->remaining(DetailFunctions::notEquals('Foo'))
            ->stream()
            ->filter(DetailFunctions::equals('Sir'))
            ->first();

        // then
        $this->assertSame('Sir', $result->text());
    }

    /**
     * @test
     */
    public function shouldReturn_keys_first_FirstMatch()
    {
        // when
        $key = pattern('\w+')->match('Foo, Bar, Dor')
            ->remaining(DetailFunctions::notEquals('Foo'))
            ->stream()
            ->filter(DetailFunctions::equals('Bar'))
            ->keys()
            ->first();

        // then
        $this->assertSame(0, $key);
    }

    /**
     * @test
     */
    public function shouldReturn_keys_first_NotFirstMatch()
    {
        // when
        $key = pattern('\w+')->match('Foo, Bar, Dor, Ver, Sir')
            ->remaining(DetailFunctions::notEquals('Foo'))
            ->stream()
            ->filter(DetailFunctions::equals('Sir'))
            ->keys()
            ->first();

        // then
        $this->assertSame(0, $key);
    }
}

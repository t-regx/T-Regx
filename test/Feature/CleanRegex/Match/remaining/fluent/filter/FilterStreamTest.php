<?php
namespace Test\Feature\TRegx\CleanRegex\Match\remaining\fluent\filter;

use PHPUnit\Framework\TestCase;
use Test\Utils\Functions;

/**
 * @coversNothing
 */
class FilterStreamTest extends TestCase
{
    /**
     * @test
     */
    public function shouldReturn_nth()
    {
        // when
        $result = pattern('\w+')->match('Lorem ipsum dolor emet')
            ->remaining(Functions::notEquals('Lorem'))
            ->fluent()
            ->filter(Functions::notEquals('ipsum'))
            ->nth(1);

        // then
        $this->assertSame('emet', $result->text());
    }

    /**
     * @test
     */
    public function shouldNotCall_first_OnUnmatchedSubject()
    {
        // when
        pattern('Foo')->match('Bar')
            ->remaining(Functions::fail())
            ->fluent()
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
            ->remaining(Functions::notEquals('Foo'))
            ->fluent()
            ->filter(Functions::equals('Bar'))
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
            ->remaining(Functions::notEquals('Foo'))
            ->fluent()
            ->filter(Functions::equals('Sir'))
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
            ->remaining(Functions::notEquals('Foo'))
            ->fluent()
            ->filter(Functions::equals('Bar'))
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
            ->remaining(Functions::notEquals('Foo'))
            ->fluent()
            ->filter(Functions::equals('Sir'))
            ->keys()
            ->first();

        // then
        $this->assertSame(0, $key);
    }
}

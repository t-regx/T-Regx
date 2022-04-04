<?php
namespace Test\Feature\CleanRegex\Match\group\asInt\_unmatched\_custom;

use PHPUnit\Framework\TestCase;
use Test\Utils\ExampleException;
use Test\Utils\Functions;

class MatchGroupIntStreamTest extends TestCase
{
    /**
     * @test
     */
    public function shouldThrow_asInt()
    {
        // given
        $optional = pattern('(Foo)')->match('Bar')->group(1)->asInt()->filter(Functions::fail())->findFirst(Functions::fail());
        // then
        $this->expectException(ExampleException::class);
        // when
        $optional->orThrow(new ExampleException());
    }

    /**
     * @test
     */
    public function shouldThrow_asInt_asInt()
    {
        // given
        $optional = pattern('(Foo)')->match('Bar')->group(1)->asInt()->filter(Functions::fail())->asInt()->findFirst(Functions::fail());
        // then
        $this->expectException(ExampleException::class);
        // when
        $optional->orThrow(new ExampleException());
    }

    /**
     * @test
     */
    public function shouldThrow_asInt_distinct()
    {
        // given
        $optional = pattern('(Foo)')->match('Bar')->group(1)->asInt()->filter(Functions::fail())->distinct()->findFirst(Functions::fail());
        // then
        $this->expectException(ExampleException::class);
        // when
        $optional->orThrow(new ExampleException());
    }
}

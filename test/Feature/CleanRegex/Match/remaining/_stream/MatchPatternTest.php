<?php
namespace Test\Feature\TRegx\CleanRegex\Match\remaining\_stream;

use PHPUnit\Framework\TestCase;
use Test\Utils\AssertsSameMatches;
use Test\Utils\Functions;

class MatchPatternTest extends TestCase
{
    use AssertsSameMatches;

    /**
     * @test
     */
    public function shouldGet_fluent_asInt_all()
    {
        // when
        $all = pattern('\d+')->match('18 19')->remaining(Functions::equals('19'))->fluent()->asInt()->all();

        // then
        $this->assertSame([1 => 19], $all);
    }

    /**
     * @test
     */
    public function shouldGet_fluent_keys_all()
    {
        // when
        $keys = pattern('\d+')->match('18 19')->remaining(Functions::equals('19'))->fluent()->keys()->all();

        // then
        $this->assertSame([1], $keys);
    }

    /**
     * @test
     */
    public function shouldGet_fluent_keys_first()
    {
        // when
        $keys = pattern('\d+')->match('18 19 20')->remaining(Functions::equals('20'))->fluent()->keys()->first();

        // then
        $this->assertSame(2, $keys);
    }
}

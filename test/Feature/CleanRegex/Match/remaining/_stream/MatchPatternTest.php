<?php
namespace Test\Feature\TRegx\CleanRegex\Match\remaining\_stream;

use PHPUnit\Framework\TestCase;
use Test\Utils\Functions;

/**
 * @coversNothing
 */
class MatchPatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGet_fluent_asInt_all()
    {
        // when
        $all = pattern('\d+')->match('18 19')->remaining(Functions::equals('19'))->fluent()->asInt()->all();

        // then
        $this->assertSame([19], $all);
    }

    /**
     * @test
     */
    public function shouldGet_fluent_keys_all()
    {
        // when
        $keys = pattern('\d+')->match('18 19')->remaining(Functions::equals('19'))->fluent()->keys()->all();

        // then
        $this->assertSame([0], $keys);
    }

    /**
     * @test
     */
    public function shouldGet_fluent_keys_first()
    {
        // when
        $keys = pattern('\d+')->match('18 19 20')->remaining(Functions::equals('20'))->fluent()->keys()->first();

        // then
        $this->assertSame(0, $keys);
    }

    /**
     * @test
     */
    public function shouldGet_offsets_fluent_keys_first()
    {
        // given
        $firstKey = pattern('\w+')->match('One Two Three')
            ->remaining(Functions::oneOf(['Two', 'Three']))
            ->offsets()
            ->fluent()
            ->keys()
            ->first();

        // when
        $this->assertSame(0, $firstKey);
    }

    /**
     * @test
     */
    public function shouldGet_offsets_fluent_keys_all()
    {
        // given
        $keys = pattern('\w+')->match('One Two Three')
            ->remaining(Functions::oneOf(['Two', 'Three']))
            ->offsets()
            ->fluent()
            ->keys()
            ->all();

        // when
        $this->assertSame([0, 1], $keys);
    }
}

<?php
namespace Test\Feature\TRegx\CleanRegex\Match\remaining\_stream;

use PHPUnit\Framework\TestCase;
use Test\Utils\Functions;

class MatchPatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGet_stream_asInt_all()
    {
        // when
        $all = pattern('\d+')->match('18 19')->remaining(Functions::equals('19'))->stream()->asInt()->all();

        // then
        $this->assertSame([19], $all);
    }

    /**
     * @test
     */
    public function shouldGet_stream_keys_all()
    {
        // when
        $keys = pattern('\d+')->match('18 19')->remaining(Functions::equals('19'))->stream()->keys()->all();

        // then
        $this->assertSame([0], $keys);
    }

    /**
     * @test
     */
    public function shouldGet_stream_keys_first()
    {
        // when
        $keys = pattern('\d+')->match('18 19 20')->remaining(Functions::equals('20'))->stream()->keys()->first();

        // then
        $this->assertSame(0, $keys);
    }

    /**
     * @test
     */
    public function shouldGet_offsets_keys_first()
    {
        // given
        $firstKey = pattern('\w+')->match('One Two Three')
            ->remaining(Functions::oneOf(['Two', 'Three']))
            ->offsets()
            ->keys()
            ->first();

        // when
        $this->assertSame(0, $firstKey);
    }

    /**
     * @test
     */
    public function shouldGet_offsets_keys_all()
    {
        // given
        $keys = pattern('\w+')->match('One Two Three')
            ->remaining(Functions::oneOf(['Two', 'Three']))
            ->offsets()
            ->keys()
            ->all();

        // when
        $this->assertSame([0, 1], $keys);
    }
}

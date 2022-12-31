<?php
namespace Test\Feature\CleanRegex\replace\callback\Detail\index;

use PHPUnit\Framework\TestCase;
use Test\Utils\Functions;

class Test extends TestCase
{
    /**
     * @test
     */
    public function shouldGetIndex_first()
    {
        // given
        pattern('Foo')->replace('Foo')->first()->callback(Functions::out($detail, ''));
        // when, then
        $this->assertSame(0, $detail->index());
    }

    /**
     * @test
     */
    public function shouldGetIndex_limit1()
    {
        // given
        pattern('\d+')->replace('111-222-333')->limit(1)->callback(Functions::out($detail, ''));
        // when, then
        $this->assertSame(0, $detail->index());
    }

    /**
     * @test
     */
    public function shouldGetIndex_limit2()
    {
        // given
        pattern('\d+')
            ->replace('111-222-333')
            ->limit(2)
            ->callback(Functions::collect($details, ''));
        // then
        [$first, $second] = $details;
        $this->assertSame(0, $first->index());
        $this->assertSame(1, $second->index());
    }

    /**
     * @test
     */
    public function shouldGetIndex()
    {
        // given
        pattern('\d+')->replace('111-222-333')->callback(Functions::collect($details, ''));
        // when, then
        [$first, $second, $third] = $details;
        $this->assertSame(0, $first->index());
        $this->assertSame(1, $second->index());
        $this->assertSame(2, $third->index());
    }
}

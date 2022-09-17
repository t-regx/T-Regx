<?php
namespace Test\Feature\CleanRegex\Replace\callback\Detail\index;

use PHPUnit\Framework\TestCase;
use Test\Utils\DetailFunctions;
use Test\Utils\Functions;

class Test extends TestCase
{
    /**
     * @test
     */
    public function shouldGetIndex_replace_first()
    {
        // given
        pattern('\d+')->replace('111-222-333')->first()->callback(DetailFunctions::out($detail, ''));
        // when
        $index = $detail->index();
        // then
        $this->assertSame(0, $index);
    }

    /**
     * @test
     */
    public function shouldGetIndex_replace()
    {
        // given
        pattern('\d+')
            ->replace('111-222-333')
            ->all()
            ->callback(Functions::collect($details, ''));
        // then
        [$first, $second, $third] = $details;
        $this->assertSame(0, $first->index());
        $this->assertSame(1, $second->index());
        $this->assertSame(2, $third->index());
    }

    /**
     * @test
     */
    public function shouldGetIndex_replace_only()
    {
        // given
        pattern('\d+')
            ->replace('111-222-333')
            ->only(3)
            ->callback(Functions::collect($details, ''));
        // then
        [$first, $second, $third] = $details;
        $this->assertSame(0, $first->index());
        $this->assertSame(1, $second->index());
        $this->assertSame(2, $third->index());
    }
}

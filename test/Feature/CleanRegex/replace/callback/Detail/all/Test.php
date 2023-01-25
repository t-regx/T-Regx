<?php
namespace Test\Feature\CleanRegex\replace\callback\Detail\all;

use PHPUnit\Framework\TestCase;
use Test\Utils\Functions;
use TRegx\CleanRegex\Match\Detail;
use TRegx\CleanRegex\Pattern;

class Test extends TestCase
{
    /**
     * @test
     */
    public function shouldGetAll()
    {
        // given
        Pattern::of('\d+')
            ->replace('123, 345, 678')
            ->callback(function (Detail $detail): string {
                // when, then
                $this->assertSame(['123', '345', '678'], $detail->all());

                // after
                return '';
            });
    }

    /**
     * @test
     */
    public function shouldGetAll_limit()
    {
        // when
        Pattern::of('\d+')
            ->replace('123, 345, 678')
            ->limit(2)
            ->callback(Functions::collect($details, ''));
        // when, then
        [$first, $second] = $details;
        $this->assertSame(['123', '345', '678'], $first->all());
        $this->assertSame(['123', '345', '678'], $second->all());
    }

    /**
     * @test
     */
    public function shouldGetAll_first()
    {
        // when
        Pattern::of('\d+')
            ->replace('123')
            ->limit(2)
            ->callback(Functions::out($details, ''));
        // when, then
        $this->assertSame(['123'], $details->all());
    }
}

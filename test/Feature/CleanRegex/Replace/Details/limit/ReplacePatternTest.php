<?php
namespace Test\Feature\TRegx\CleanRegex\Replace\Details\limit;

use PHPUnit\Framework\TestCase;
use Test\Utils\Functions;
use TRegx\CleanRegex\Pattern;

/**
 * @covers \TRegx\CleanRegex\Replace\Details\ReplaceDetail::limit
 */
class ReplacePatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldLimit_all()
    {
        // given
        $replace = Pattern::of('\d+')->replace('123');
        // when
        $replace->all()->callback(Functions::peek(Functions::assertSame(-1, Functions::property('limit')), ''));
    }

    /**
     * @test
     */
    public function shouldReplaceFirstCallback()
    {
        // given
        $replace = Pattern::of('\d+')->replace('123');
        // when
        $replace->first()->callback(Functions::peek(Functions::assertSame(1, Functions::property('limit')), ''));
    }

    /**
     * @test
     */
    public function shouldReplaceOnly2Callback()
    {
        // given
        $replace = Pattern::of('\d+')->replace('123');
        // when
        $replace->only(2)->callback(Functions::peek(Functions::assertSame(2, Functions::property('limit')), ''));
    }
}

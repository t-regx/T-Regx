<?php
namespace Test\Feature\CleanRegex\replace\callback\Detail\isInt;

use PHPUnit\Framework\TestCase;
use Test\Utils\DetailFunctions;
use TRegx\CleanRegex\Pattern;

class Test extends TestCase
{
    /**
     * @test
     */
    public function shouldBeInteger()
    {
        // given
        Pattern::of('1094')->replace('1094')->first()->callback(DetailFunctions::out($detail, ''));
        // when, then
        $this->assertTrue($detail->isInt());
    }

    /**
     * @test
     */
    public function shouldBeIntegerBase11()
    {
        // given
        Pattern::of('9a')->replace('9a')->first()->callback(DetailFunctions::out($detail, ''));
        // when, then
        $this->assertTrue($detail->isInt(11));
    }

    /**
     * @test
     */
    public function shouldNotBeIntegerBase10()
    {
        // given
        Pattern::of('a0')
            ->replace('a0')
            ->first()
            ->callback(DetailFunctions::out($detail, ''));
        // when, then
        $this->assertFalse($detail->isInt());
    }

    /**
     * @test
     */
    public function shouldNotBeIntegerBase9()
    {
        // given
        Pattern::of('9')->replace('9')->first()->callback(DetailFunctions::out($detail, ''));
        // when, then
        $this->assertFalse($detail->isInt(9));
    }
}

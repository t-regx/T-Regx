<?php
namespace Test\Feature\CleanRegex\Replace\Details\isInt;

use PHPUnit\Framework\TestCase;
use Test\Utils\DetailFunctions;
use function pattern;

class ReplacePatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldBeInteger()
    {
        // given
        pattern('1094')->replace('1094')->first()->callback(DetailFunctions::out($detail, ''));
        // when, then
        $this->assertTrue($detail->isInt());
    }

    /**
     * @test
     */
    public function shouldBeIntegerBase11()
    {
        // given
        pattern('9a')->replace('9a')->first()->callback(DetailFunctions::out($detail, ''));
        // when, then
        $this->assertTrue($detail->isInt(11));
    }

    /**
     * @test
     */
    public function shouldNotBeIntegerBase10()
    {
        // given
        pattern('a0')
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
        pattern('9')->replace('9')->first()->callback(DetailFunctions::out($detail, ''));
        // when, then
        $this->assertFalse($detail->isInt(9));
    }
}

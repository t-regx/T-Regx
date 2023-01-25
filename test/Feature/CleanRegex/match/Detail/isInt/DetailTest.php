<?php
namespace Test\Feature\CleanRegex\match\Detail\isInt;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Pattern;

class DetailTest extends TestCase
{
    /**
     * @test
     */
    public function shouldBeInt()
    {
        // given
        $detail = Pattern::of('(?<name>\d+)')->match('11')->first();
        // when, then
        $this->assertTrue($detail->isInt());
    }

    /**
     * @test
     */
    public function shouldPseudoInteger_notBeInt_becausePhpSucks()
    {
        // given
        $detail = Pattern::of('(.*)', 's')->match('1e3')->first();
        // when, then
        $this->assertFalse($detail->isInt());
    }

    /**
     * @test
     */
    public function shouldNotBeIntegerMalformed()
    {
        // given
        $detail = Pattern::of('Foo')->match('Foo')->first();
        // when, then
        $this->assertFalse($detail->isInt());
    }

    /**
     * @test
     */
    public function shouldInteger11NotBeIntegerinBase10()
    {
        // given
        $detail = Pattern::of('10a1')->match('10a1')->first();
        // when, then
        $this->assertFalse($detail->isInt());
    }

    /**
     * @test
     */
    public function shouldBeIntegerBase10()
    {
        // given
        $detail = Pattern::of('19')->match('19')->first();
        // when, then
        $this->assertTrue($detail->isInt());
    }

    /**
     * @test
     */
    public function shouldBeIntegerBase16()
    {
        // given
        $detail = Pattern::of('19af')->match('19af')->first();
        // when, then
        $this->assertTrue($detail->isInt(16));
    }

    /**
     * @test
     */
    public function shouldThrowForInvalidBase()
    {
        // given
        $detail = Pattern::of('Foo')->match('Foo')->first();
        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid base: 0 (supported bases 2-36, case-insensitive)');
        // when
        $detail->isInt(0);
    }

    /**
     * @test
     */
    public function shouldNotBeIntegerOverflown()
    {
        // given
        $detail = Pattern::of('-\d+')->match('-922337203685477580700')->first();
        // when
        $result = $detail->isInt();
        // then
        $this->assertFalse($result);
    }
}

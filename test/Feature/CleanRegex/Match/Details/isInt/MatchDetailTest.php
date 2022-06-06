<?php
namespace Test\Feature\CleanRegex\Match\Details\isInt;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Match\Details\Detail;
use function pattern;

class MatchDetailTest extends TestCase
{
    /**
     * @test
     */
    public function shouldBeInt()
    {
        // given
        $result = pattern('(?<name>\d+)')->match('11')->first(function (Detail $detail) {
            // when
            return $detail->isInt();
        });

        // then
        $this->assertTrue($result);
    }

    /**
     * @test
     */
    public function shouldPseudoInteger_notBeInt_becausePhpSucks()
    {
        // given
        $result = pattern('(.*)', 's')->match('1e3')->first(function (Detail $detail) {
            // when
            return $detail->isInt();
        });

        // then
        $this->assertFalse($result);
    }

    /**
     * @test
     */
    public function shouldNotBeIntegerMalformed()
    {
        // given
        pattern('Foo')->match('Foo')->first(function (Detail $detail) {
            // when
            $result = $detail->isInt();

            // then
            $this->assertFalse($result);
        });
    }

    /**
     * @test
     */
    public function shouldInteger11NotBeIntegerinBase10()
    {
        // given
        pattern('10a1')->match('10a1')->first(function (Detail $detail) {
            // when
            $result = $detail->isInt();

            // then
            $this->assertFalse($result);
        });
    }

    /**
     * @test
     */
    public function shouldBeIntegerBase10()
    {
        // given
        pattern('19')->match('19')->first(function (Detail $detail) {
            // when
            $result = $detail->isInt();

            // then
            $this->assertTrue($result);
        });
    }

    /**
     * @test
     */
    public function shouldBeIntegerBase16()
    {
        // given
        pattern('19af')->match('19af')->first(function (Detail $detail) {
            // when
            $result = $detail->isInt(16);

            // then
            $this->assertTrue($result);
        });
    }

    /**
     * @test
     */
    public function shouldThrowForInvalidBase()
    {
        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid base: 0 (supported bases 2-36, case-insensitive)');

        // given
        pattern('Foo')->match('Foo')->first(function (Detail $detail) {
            // when
            $detail->isInt(0);
        });
    }

    /**
     * @test
     */
    public function shouldNotBeIntegerOverflown()
    {
        // given
        pattern('-\d+')->match('-922337203685477580700')->first(function (Detail $detail) {
            // when
            $result = $detail->isInt();

            // then
            $this->assertFalse($result);
        });
    }
}

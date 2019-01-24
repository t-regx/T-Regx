<?php
namespace Test\Feature\TRegx\CleanRegex\Match\Details\group\isInt;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Exception\CleanRegex\GroupNotMatchedException;
use TRegx\CleanRegex\Match\Details\Match;

class MatchImplTest extends TestCase
{
    /**
     * @test
     */
    public function shouldBsInt()
    {
        // given
        $result = pattern('(?<name>-?\w+)')
            ->match('11')
            ->first(function (Match $match) {
                // when
                return $match->group(1)->isInt();
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
        $result = pattern('(.*)', 's')
            ->match('1e3')
            ->first(function (Match $match) {
                // when
                return $match->group(1)->isInt();
            });

        // then
        $this->assertFalse($result);
    }

    /**
     * @test
     */
    public function shouldBesInt_byName()
    {
        // given
        pattern('(?<value>\d+)')
            ->match('12cm 14mm 13cm 19cm 18mm 2mm')
            ->forEach(function (Match $match) {
                // when
                $isInt = $match->group('value')->isInt();

                // then
                $this->assertTrue($isInt);
            });
    }

    /**
     * @test
     */
    public function shouldBsInt_byIndex()
    {
        // given
        pattern('(?<value>\d+)')
            ->match('12cm 14mm 13cm 19cm 18mm 2mm')
            ->map(function (Match $match) {
                // when
                $isInt = $match->group(1)->isInt();

                // then
                $this->assertTrue($isInt);
            });
    }

    /**
     * @test
     */
    public function shouldNotBeInteger_byName()
    {
        // given
        pattern('(?<name>\w+)')
            ->match('Foo bar')
            ->first(function (Match $match) {
                // when
                $result = $match->group('name')->isInt();

                // then
                $this->assertFalse($result);
            });
    }

    /**
     * @test
     */
    public function shouldNotBeInteger_byIndex()
    {
        // given
        pattern('(?<name>\w+)')
            ->match('Foo bar')
            ->first(function (Match $match) {
                // when
                $result = $match->group(1)->isInt();

                // then
                $this->assertFalse($result);
            });
    }

    /**
     * @test
     */
    public function shouldThrowForUnmatchedGroup()
    {
        // then
        $this->expectException(GroupNotMatchedException::class);
        $this->expectExceptionMessage("Expected to call isInt() for group 'missing', but group was not matched");

        // given
        pattern('(?<name>\w+)(?<missing>\d+)?')
            ->match('Foo bar')
            ->first(function (Match $match) {
                // when
                return $match->group('missing')->isInt();
            });
    }
}

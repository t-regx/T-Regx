<?php
namespace Test\Feature\TRegx\CleanRegex\Match\Details\group\isInt;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\CleanRegex\Match\Details\Detail;

class MatchDetailTest extends TestCase
{
    /**
     * @test
     */
    public function shouldBeInt()
    {
        // given
        $result = pattern('(?<name>\d+)')
            ->match('11')
            ->first(function (Detail $detail) {
                // when
                return $detail->group(1)->isInt();
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
        $result = pattern('(1e3)')
            ->match('1e3')
            ->first(function (Detail $detail) {
                // when
                return $detail->group(1)->isInt();
            });

        // then
        $this->assertFalse($result);
    }

    /**
     * @test
     */
    public function shouldBeInt_byName()
    {
        // given
        pattern('(?<value>\d+)')
            ->match('12cm 14mm 13cm 19cm 18mm 2mm')
            ->forEach(function (Detail $detail) {
                // when
                $isInt = $detail->group('value')->isInt();

                // then
                $this->assertTrue($isInt);
            });
    }

    /**
     * @test
     */
    public function shouldBeInt_byIndex()
    {
        // given
        pattern('(?<value>\d+)')
            ->match('12cm 14mm 13cm 19cm 18mm 2mm')
            ->map(function (Detail $detail) {
                // when
                $isInt = $detail->group(1)->isInt();

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
        pattern('(?<name>Foo)')
            ->match('Foo bar')
            ->first(function (Detail $detail) {
                // when
                $result = $detail->group('name')->isInt();

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
        pattern('(?<name>Foo)')
            ->match('Foo bar')
            ->first(function (Detail $detail) {
                // when
                $result = $detail->group(1)->isInt();

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
        $this->expectExceptionMessage("Expected to call isInt() for group 'missing', but the group was not matched");

        // given
        pattern('(?<name>Foo)(?<missing>\d+)?')
            ->match('Foo bar')
            ->first(function (Detail $detail) {
                // when
                return $detail->group('missing')->isInt();
            });
    }
}

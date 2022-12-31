<?php
namespace Test\Feature\CleanRegex\match\Detail\group\isInt;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\CleanRegex\Match\Detail;
use function pattern;

class DetailTest extends TestCase
{
    /**
     * @test
     */
    public function shouldBeInt()
    {
        // given
        $detail = pattern('(?<name>11)')->match('11')->first();
        // when
        $result = $detail->group(1)->isInt();
        // then
        $this->assertTrue($result);
    }

    /**
     * @test
     */
    public function shouldPseudoInteger_notBeInt_becausePhpSucks()
    {
        // given
        $detail = pattern('(1e3)')->match('1e3')->first();
        // when
        $result = $detail->group(1)->isInt();
        // then
        $this->assertFalse($result);
    }

    /**
     * @test
     */
    public function shouldNotBeInteger_byName()
    {
        // given
        $detail = pattern('(?<name>Foo)')->match('Foo bar')->first();
        // when
        $result = $detail->group('name')->isInt();
        // then
        $this->assertFalse($result);
    }

    /**
     * @test
     */
    public function shouldNotBeInteger_byIndex()
    {
        // given
        $detail = pattern('(?<name>Foo)')->match('Foo bar')->first();
        // when
        $result = $detail->group(1)->isInt();
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
    public function shouldBeIntBase12()
    {
        // given
        $detail = pattern('14b2')->match('14b2')->first();
        // when
        $isInt = $detail->group(0)->isInt(12);
        // then
        $this->assertTrue($isInt, "Failed asserting that {$detail->subject()} is int in base 12");
    }

    /**
     * @test
     */
    public function shouldIntegerBase11NotBeIntegerBase10()
    {
        // given
        $detail = pattern('10a')->match('10a')->first();
        // when
        $isInt = $detail->group(0)->isInt();
        // then
        $this->assertFalse($isInt);
    }

    /**
     * @test
     */
    public function shouldNotBeInteger_overflown()
    {
        // given
        $detail = pattern('(?<name>\d+)')->match('-92233720368547758080')->first();
        // when
        $result = $detail->group('name')->isInt();
        // then
        $this->assertFalse($result);
    }

    /**
     * @test
     */
    public function shouldThrowForUnmatchedGroup()
    {
        // given
        $detail = pattern('(?<name>Foo)(?<missing>Bar)?')->match('Foo')->first();
        // then
        $this->expectException(GroupNotMatchedException::class);
        $this->expectExceptionMessage("Expected to call isInt() for group 'missing', but the group was not matched");
        // when
        $detail->group('missing')->isInt();
    }
}

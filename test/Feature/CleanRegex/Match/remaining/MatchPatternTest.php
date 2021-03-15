<?php
namespace Test\Feature\TRegx\CleanRegex\Match\remaining;

use PHPUnit\Framework\TestCase;
use Test\Utils\AssertsSameMatches;
use Test\Utils\Functions;
use TRegx\CleanRegex\Match\Details\Detail;

class MatchPatternTest extends TestCase
{
    use AssertsSameMatches;

    /**
     * @test
     */
    public function shouldGet_test()
    {
        // when
        $matched = pattern('[A-Z][a-z]+')->match('First, Second, Third')->remaining(Functions::equals('Third'))->test();

        // then
        $this->assertTrue($matched);
    }

    /**
     * @test
     */
    public function shouldGet_test_filteredOut()
    {
        // when
        $matched = pattern('[A-Z][a-z]+')->match('First, Second')->remaining(Functions::constant(false))->test();

        // then
        $this->assertFalse($matched);
    }

    /**
     * @test
     */
    public function shouldGet_test_notMatched()
    {
        // when
        $matched = pattern('Foo')->match('Bar')->remaining(Functions::fail())->test();

        // then
        $this->assertFalse($matched);
    }

    /**
     * @test
     */
    public function shouldReturn_all()
    {
        // when
        $filtered = pattern('[A-Z][a-z]+')->match('First, Second, Third, Fourth, Fifth')
            ->remaining(function (Detail $detail) {
                return strlen($detail) === 5;
            })
            ->all();

        // then
        $this->assertSame(['First', 'Third', 'Fifth'], $filtered);
    }

    /**
     * @test
     */
    public function shouldReturn_only2()
    {
        // when
        $filtered = pattern('[A-Z][a-z]+')->match('First, Second, Third, Fourth, Fifth')
            ->remaining(function (Detail $detail) {
                return strlen($detail) === 5;
            })
            ->only(2);

        // then
        $this->assertSame(['First', 'Third'], $filtered);
    }

    /**
     * @test
     */
    public function shouldReturn_only1()
    {
        // when
        $filtered = pattern('[A-Z][a-z]+')->match('First, Second, Third, Fourth, Fifth')
            ->remaining(function (Detail $detail) {
                return strlen($detail) === 5;
            })
            ->only(1);

        // then
        $this->assertSame(['First'], $filtered);
    }

    /**
     * @test
     */
    public function shouldReturn_only1_filteredOut()
    {
        // when
        $filtered = pattern('[A-Z][a-z]+')->match('First, Second, Third')->remaining(Functions::constant(false))->only(1);

        // then
        $this->assertEmpty($filtered);
    }

    /**
     * @test
     */
    public function shouldGet_first()
    {
        // when
        $first = pattern('[A-Z][a-z]+')->match('First, Second, Third')->remaining(Functions::notEquals('First'))->first();

        // then
        $this->assertSame('Second', $first);
    }

    /**
     * @test
     */
    public function shouldGet_nth()
    {
        // when
        $result = pattern('\d+(cm|mm)')->match('12cm 14mm 13cm 19cm')->remaining(Functions::notEquals('14mm'))->nth(2);

        // then
        $this->assertSame('19cm', $result);
    }

    /**
     * @test
     */
    public function shouldReturn_count()
    {
        // when
        $filtered = pattern('[A-Z][a-z]+')->match('First, Second, Third')->remaining(Functions::notEquals('Second'))->count();

        // then
        $this->assertSame(2, $filtered);
    }

    /**
     * @test
     */
    public function shouldBe_Countable()
    {
        // when
        $count = count(pattern('\w+')->match('One, two, three')->remaining(Functions::notEquals('two')));

        // then
        $this->assertSame(2, $count);
    }

    /**
     * @test
     */
    public function shouldGet_offset_getIterator()
    {
        // when
        $iterator = pattern('\w+')->match('One, two, three')
            ->remaining(Functions::notEquals('two'))
            ->offsets()
            ->getIterator();

        // then
        $this->assertSame([0, 10], iterator_to_array($iterator));
    }
}

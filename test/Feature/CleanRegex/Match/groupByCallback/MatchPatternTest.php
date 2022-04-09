<?php
namespace Test\Feature\TRegx\CleanRegex\Match\groupByCallback;

use PHPUnit\Framework\TestCase;
use Test\Utils\AssertsSameMatches;
use Test\Utils\CausesBacktracking;
use Test\Utils\ExactExceptionMessage;
use Test\Utils\Functions;
use TRegx\CleanRegex\Exception\InvalidReturnValueException;
use TRegx\CleanRegex\Match\Details\Detail;
use function pattern;

class MatchPatternTest extends TestCase
{
    use AssertsSameMatches, CausesBacktracking, ExactExceptionMessage;

    /**
     * @test
     */
    public function shouldGroupByCallbackString()
    {
        // when
        $result = pattern('(?<value>\d+)(?<unit>cm|mm)')
            ->match('12cm 14mm 13cm 19cm 18mm 2mm')
            ->groupByCallback(function (Detail $detail) {
                return $detail->get('unit');
            });

        // then
        $expected = [
            'cm' => ['12cm', '13cm', '19cm'],
            'mm' => ['14mm', '18mm', '2mm']
        ];
        $this->assertSame($expected, $result);
    }

    /**
     * @test
     */
    public function shouldGroupByCallbackGroup()
    {
        // when
        $result = pattern('(?<value>\d+)(?<unit>cm|mm)')
            ->match('12cm 14mm 13cm 19cm 18mm 2mm')
            ->groupByCallback(function (Detail $detail) {
                return $detail->group('unit');
            });

        // then
        $expected = [
            'cm' => ['12cm', '13cm', '19cm'],
            'mm' => ['14mm', '18mm', '2mm']
        ];
        $this->assertSame($expected, $result);
    }

    /**
     * @test
     */
    public function shouldGroupByCallbackDetail()
    {
        // when
        $result = pattern('(?<value>\d+)(?<unit>cm|mm)')
            ->match('12cm 14mm 13cm 19cm 18mm 2mm')
            ->groupByCallback(Functions::identity());

        // then
        $expected = [
            '12cm' => ['12cm'],
            '14mm' => ['14mm'],
            '13cm' => ['13cm'],
            '19cm' => ['19cm'],
            '18mm' => ['18mm'],
            '2mm'  => ['2mm'],
        ];
        $this->assertSame($expected, $result);
    }

    /**
     * @test
     */
    public function shouldGroupByCallbackInteger()
    {
        // when
        $result = pattern('(?<value>\d+)(?<unit>cm|mm)')
            ->match('12cm 14mm 13cm 19cm 18mm 2mm')
            ->groupByCallback(function (Detail $detail) {
                return $detail->group('value')->toInt();
            });
        // then
        $expected = [
            12 => ['12cm'],
            14 => ['14mm'],
            13 => ['13cm'],
            19 => ['19cm'],
            18 => ['18mm'],
            2  => ['2mm'],
        ];
        $this->assertSame($expected, $result);
    }

    /**
     * @test
     */
    public function shouldThrowForInvalidGroupByTypeArray()
    {
        // then
        $this->expectException(InvalidReturnValueException::class);
        $this->expectExceptionMessage('Invalid groupByCallback() callback return type. Expected int|string, but array (0) given');
        // given
        pattern('Foo')->match('Foo')->groupByCallback(Functions::constant([]));
    }

    /**
     * @test
     */
    public function shouldThrowForInvalidGroupByTypeNull()
    {
        // then
        $this->expectException(InvalidReturnValueException::class);
        $this->expectExceptionMessage('Invalid groupByCallback() callback return type. Expected int|string, but null given');
        // given
        pattern('Foo')->match('Foo')->groupByCallback(Functions::constant(null));
    }

    /**
     * @test
     */
    public function shouldThrowForInvalidGroupByTypeTrue()
    {
        // then
        $this->expectException(InvalidReturnValueException::class);
        $this->expectExceptionMessage('Invalid groupByCallback() callback return type. Expected int|string, but boolean (true) given');
        // given
        pattern('Foo')->match('Foo')->groupByCallback(Functions::constant(true));
    }
}

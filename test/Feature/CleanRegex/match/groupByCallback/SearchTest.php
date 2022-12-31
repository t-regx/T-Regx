<?php
namespace Test\Feature\CleanRegex\match\groupByCallback;

use PHPUnit\Framework\TestCase;
use Test\Utils\Functions;
use Test\Utils\TestCase\TestCaseExactMessage;
use Test\Utils\TestCase\TestCasePasses;
use Test\Utils\TypeFunctions;
use TRegx\CleanRegex\Exception\InvalidReturnValueException;
use TRegx\CleanRegex\Pattern;
use function pattern;

/**
 * @covers \TRegx\CleanRegex\Match\Search
 */
class SearchTest extends TestCase
{
    use TestCasePasses, TestCaseExactMessage;

    /**
     * @test
     */
    public function shouldGroupByCallbackString()
    {
        // given
        $search = pattern('\d+[cm]m')->search('12cm, 14mm, 23cm, 19cm, 28mm, 2mm');
        // when
        $grouped = $search->groupByCallback(Functions::charAt(0));
        // then
        $expected = [
            '1' => ['12cm', '14mm', '19cm'],
            '2' => ['23cm', '28mm', '2mm']
        ];
        $this->assertSame($expected, $grouped);
    }

    /**
     * @test
     */
    public function shouldGroupByCallbackStringIdentity()
    {
        // given
        $search = pattern('(?<value>\d+)(?<unit>cm|mm)')->search('12cm 14mm 13cm 19cm 18mm 2mm');
        // when
        $grouped = $search->groupByCallback(Functions::identity());
        // then
        $expected = [
            '12cm' => ['12cm'],
            '14mm' => ['14mm'],
            '13cm' => ['13cm'],
            '19cm' => ['19cm'],
            '18mm' => ['18mm'],
            '2mm'  => ['2mm'],
        ];
        $this->assertSame($expected, $grouped);
    }

    /**
     * @test
     */
    public function shouldGroupByWithDetailArgument()
    {
        // when
        Pattern::of('Foo')->search('Foo')->groupByCallback(TypeFunctions::assertTypeString(0));
        // then
        $this->pass();
    }

    /**
     * @test
     */
    public function shouldGroupByCallbackInteger()
    {
        // given
        $search = pattern('\d+')->search('12, 14, 12, 18, 2');
        // when
        $grouped = $search->groupByCallback(Functions::toInt());
        // then
        $expected = [
            12 => ['12', '12'],
            14 => ['14'],
            18 => ['18'],
            2  => ['2'],
        ];
        $this->assertSame($expected, $grouped);
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
        pattern('Foo')->search('Foo')->groupByCallback(Functions::constant([]));
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
        pattern('Foo')->search('Foo')->groupByCallback(Functions::constant(null));
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
        pattern('Foo')->search('Foo')->groupByCallback(Functions::constant(true));
    }

    /**
     * @test
     */
    public function shouldGroupByUnmatched()
    {
        // when
        $grouped = pattern('Foo')->search('Bar')->groupByCallback(Functions::fail());
        // then
        $this->assertEmpty($grouped);
    }
}

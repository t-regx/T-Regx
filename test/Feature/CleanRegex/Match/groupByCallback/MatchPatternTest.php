<?php
namespace Test\Feature\CleanRegex\Match\groupByCallback;

use PHPUnit\Framework\TestCase;
use Test\Utils\DetailFunctions;
use Test\Utils\Functions;
use Test\Utils\Structure\AssertsStructure;
use Test\Utils\Structure\Expect;
use Test\Utils\TestCase\TestCaseExactMessage;
use Test\Utils\TestCase\TestCasePasses;
use Test\Utils\TypeFunctions;
use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\CleanRegex\Exception\InvalidReturnValueException;
use TRegx\CleanRegex\Match\Details\Detail;
use TRegx\CleanRegex\Pattern;
use function pattern;

/**
 * @covers \TRegx\CleanRegex\Match\MatchPattern
 */
class MatchPatternTest extends TestCase
{
    use TestCasePasses, TestCaseExactMessage, AssertsStructure;

    /**
     * @test
     */
    public function shouldGroupByCallbackString()
    {
        // given
        $match = pattern('\d+(?<unit>cm|mm)')->match('12cm, 14mm, 13cm, 19cm, 18mm, 2mm');
        // when
        $groupped = $match->groupByCallback(DetailFunctions::get('unit'));
        // then
        $this->assertStructure($groupped, [
            'cm' => [Expect::text('12cm'), Expect::text('13cm'), Expect::text('19cm')],
            'mm' => [Expect::text('14mm'), Expect::text('18mm'), Expect::text('2mm')]
        ]);
    }

    /**
     * @test
     */
    public function shouldGroupByCallbackGroup()
    {
        // given
        $match = pattern('\d+(?<unit>cm|mm)')->match('12cm 14mm 13cm 19cm 18mm 2mm');
        // when
        $groupped = $match->groupByCallback(DetailFunctions::group('unit'));
        // then
        $this->assertStructure($groupped, [
            'cm' => [Expect::text('12cm'), Expect::text('13cm'), Expect::text('19cm')],
            'mm' => [Expect::text('14mm'), Expect::text('18mm'), Expect::text('2mm')]
        ]);
    }

    /**
     * @test
     */
    public function shouldGroupByCallbackDetail()
    {
        // given
        $match = pattern('(?<value>\d+)(?<unit>cm|mm)')->match('12cm 14mm 13cm 19cm 18mm 2mm');
        // when
        $groupped = $match->groupByCallback(Functions::identity());
        // then
        $this->assertStructure($groupped, [
            '12cm' => [Expect::text('12cm')],
            '14mm' => [Expect::text('14mm')],
            '13cm' => [Expect::text('13cm')],
            '19cm' => [Expect::text('19cm')],
            '18mm' => [Expect::text('18mm')],
            '2mm'  => [Expect::text('2mm')],
        ]);
    }

    /**
     * @test
     */
    public function shouldGroupByWithDetailArgument()
    {
        // when
        Pattern::of('Foo')->match('Foo')->groupByCallback(TypeFunctions::assertTypeDetail(0));
        // then
        $this->pass();
    }

    /**
     * @test
     */
    public function shouldGroupByCallbackInteger()
    {
        // given
        $match = pattern('(?<value>\d+)(cm|mm)')->match('12cm 14mm 13cm 19cm 18mm 2mm');
        // when
        $groupped = $match->groupByCallback(function (Detail $detail) {
            return $detail->group('value')->toInt();
        });
        // then
        $this->assertStructure($groupped, [
            12 => [Expect::text('12cm')],
            14 => [Expect::text('14mm')],
            13 => [Expect::text('13cm')],
            19 => [Expect::text('19cm')],
            18 => [Expect::text('18mm')],
            2  => [Expect::text('2mm')],
        ]);
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

    /**
     * @test
     */
    public function shouldGroupByUnmatched()
    {
        // when
        $groupped = pattern('Foo')->match('Bar')->groupByCallback(Functions::fail());
        // then
        $this->assertEmpty($groupped);
    }

    /**
     * @test
     */
    public function shouldGroupByUnmatchedGroup()
    {
        // given
        $match = Pattern::of('Foo(?<missing>missing)?')->match('Foo');
        // then
        $this->expectException(GroupNotMatchedException::class);
        $this->expectExceptionMessage("Expected to group matches by group 'missing', but the group was not matched");
        // when
        $match->groupByCallback(function (Detail $detail) {
            return $detail->group('missing');
        });
    }

    /**
     * @test
     */
    public function shouldGroupByUnmatchedGroupIndexed()
    {
        // given
        $match = Pattern::of('Foo(?<missing>missing)?')->match('Foo');
        // then
        $this->expectException(GroupNotMatchedException::class);
        $this->expectExceptionMessage("Expected to group matches by group #1, but the group was not matched");
        // when
        $match->groupByCallback(function (Detail $detail) {
            return $detail->group(1);
        });
    }
}

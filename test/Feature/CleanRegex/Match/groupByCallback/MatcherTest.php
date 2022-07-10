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
 * @covers \TRegx\CleanRegex\Match\Matcher
 */
class MatcherTest extends TestCase
{
    use TestCasePasses, TestCaseExactMessage, AssertsStructure;

    /**
     * @test
     */
    public function shouldGroupByCallbackString()
    {
        // given
        $matcher = pattern('\d+(?<unit>cm|mm)')->match('12cm, 14mm, 13cm, 19cm, 18mm, 2mm');
        // when
        $groupped = $matcher->groupByCallback(DetailFunctions::get('unit'));
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
        $matcher = pattern('\d+(?<unit>cm|mm)')->match('12cm 14mm 13cm 19cm 18mm 2mm');
        // when
        $groupped = $matcher->groupByCallback(DetailFunctions::group('unit'));
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
        $matcher = pattern('(?<value>\d+)(?<unit>cm|mm)')->match('12cm 14mm 13cm 19cm 18mm 2mm');
        // when
        $groupped = $matcher->groupByCallback(Functions::identity());
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
        // given
        $matcher = Pattern::of('Foo')->match('Foo');
        // when
        $matcher->groupByCallback(TypeFunctions::assertTypeDetail(0));
        // then
        $this->pass();
    }

    /**
     * @test
     */
    public function shouldGroupByCallbackInteger()
    {
        // given
        $matcher = pattern('(?<value>\d+)(cm|mm)')->match('12cm 14mm 13cm 19cm 18mm 2mm');
        // when
        $groupped = $matcher->groupByCallback(function (Detail $detail) {
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
        // given
        $matcher = pattern('Foo')->match('Foo');
        // then
        $this->expectException(InvalidReturnValueException::class);
        $this->expectExceptionMessage('Invalid groupByCallback() callback return type. Expected int|string, but boolean (true) given');
        // when
        $matcher->groupByCallback(Functions::constant(true));
    }

    /**
     * @test
     */
    public function shouldGroupByUnmatched()
    {
        // given
        $matcher = pattern('Foo')->match('Bar');
        // when
        $groupped = $matcher->groupByCallback(Functions::fail());
        // then
        $this->assertEmpty($groupped);
    }

    /**
     * @test
     */
    public function shouldGroupByUnmatchedGroup()
    {
        // given
        $matcher = Pattern::of('Foo(?<missing>missing)?')->match('Foo');
        // then
        $this->expectException(GroupNotMatchedException::class);
        $this->expectExceptionMessage("Expected to group matches by group 'missing', but the group was not matched");
        // when
        $matcher->groupByCallback(function (Detail $detail) {
            return $detail->group('missing');
        });
    }

    /**
     * @test
     */
    public function shouldGroupByUnmatchedGroupIndexed()
    {
        // given
        $matcher = Pattern::of('Foo(?<missing>missing)?')->match('Foo');
        // then
        $this->expectException(GroupNotMatchedException::class);
        $this->expectExceptionMessage("Expected to group matches by group #1, but the group was not matched");
        // when
        $matcher->groupByCallback(function (Detail $detail) {
            return $detail->group(1);
        });
    }
}

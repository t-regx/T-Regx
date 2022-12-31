<?php
namespace Test\Feature\CleanRegex\match\filter;

use PHPUnit\Framework\TestCase;
use Test\Utils\Assertion\AssertsDetail;
use Test\Utils\DetailFunctions;
use Test\Utils\Functions;
use Test\Utils\Structure\AssertsStructure;
use Test\Utils\Structure\Expect;
use Test\Utils\TestCase\TestCasePasses;
use Test\Utils\TypeFunctions;
use TRegx\CleanRegex\Exception\InvalidReturnValueException;
use TRegx\CleanRegex\Pattern;
use TRegx\Exception\MalformedPatternException;

/**
 * @covers \TRegx\CleanRegex\Match\Matcher
 */
class MatcherTest extends TestCase
{
    use AssertsDetail, AssertsStructure, TestCasePasses;

    /**
     * @test
     */
    public function shouldFilter()
    {
        // given
        $matcher = pattern('\w+')->match('Foo, Bar, Top, Door');
        // when
        $details = $matcher->filter(DetailFunctions::notEquals('Bar'));
        // then
        $this->assertStructure($details, [
            Expect::text('Foo'),
            Expect::text('Top'),
            Expect::text('Door'),
        ]);
        $this->assertStructure($details, [
            Expect::index(0),
            Expect::index(2),
            Expect::index(3),
        ]);
        $this->assertDetailsSubject('Foo, Bar, Top, Door', ...$details);
    }

    /**
     * @test
     */
    public function shouldFilter_acceptDetail()
    {
        // given
        $matcher = pattern('Foo')->match('Foo');
        // when
        $matcher->filter(TypeFunctions::assertTypeDetail(true));
        // then
        $this->pass();
    }

    /**
     * @test
     */
    public function shouldThrow_forInvalidReturnType_integer()
    {
        // given
        $matcher = pattern('Foo')->match('Foo');
        // then
        $this->expectException(InvalidReturnValueException::class);
        $this->expectExceptionMessage('Invalid filter() callback return type. Expected bool, but integer (12) given');
        // when
        $matcher->filter(Functions::constant(12));
    }

    /**
     * @test
     */
    public function shouldThrow_forInvalidReturnType_array()
    {
        // given
        $matcher = pattern('Foo')->match('Foo');
        // then
        $this->expectException(InvalidReturnValueException::class);
        $this->expectExceptionMessage('Invalid filter() callback return type. Expected bool, but array (0) given');
        // when
        $matcher->filter(Functions::constant([]));
    }

    /**
     * @test
     */
    public function shouldThrowForMalformedPattern()
    {
        // given
        $matcher = Pattern::of('+')->match('Foo');
        // when
        $this->expectException(MalformedPatternException::class);
        $this->expectExceptionMessage('Quantifier does not follow a repeatable item at offset 0');
        // then
        $matcher->filter(Functions::fail());
    }
}

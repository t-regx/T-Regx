<?php
namespace Test\Feature\CleanRegex\Match\filter;

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
 * @covers \TRegx\CleanRegex\Match\MatchPattern
 */
class MatchPatternTest extends TestCase
{
    use AssertsDetail, AssertsStructure, TestCasePasses;

    /**
     * @test
     */
    public function shouldFilter()
    {
        // given
        $match = pattern('\w+')->match('Foo, Bar, Top, Door');
        // when
        $details = $match->filter(DetailFunctions::notEquals('Bar'));
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
        $match = pattern('Foo')->match('Foo');
        // when
        $match->filter(TypeFunctions::assertTypeDetail(true));
        // then
        $this->pass();
    }

    /**
     * @test
     */
    public function shouldThrow_forInvalidReturnType_integer()
    {
        // given
        $match = pattern('Foo')->match('Foo');
        // then
        $this->expectException(InvalidReturnValueException::class);
        $this->expectExceptionMessage('Invalid filter() callback return type. Expected bool, but integer (12) given');
        // when
        $match->filter(Functions::constant(12));
    }

    /**
     * @test
     */
    public function shouldThrow_forInvalidReturnType_array()
    {
        // given
        $match = pattern('Foo')->match('Foo');
        // then
        $this->expectException(InvalidReturnValueException::class);
        $this->expectExceptionMessage('Invalid filter() callback return type. Expected bool, but array (0) given');
        // when
        $match->filter(Functions::constant([]));
    }

    /**
     * @test
     */
    public function shouldThrowForMalformedPattern()
    {
        // given
        $match = Pattern::of('+')->match('Foo');
        // when
        $this->expectException(MalformedPatternException::class);
        $this->expectExceptionMessage('Quantifier does not follow a repeatable item at offset 0');
        // then
        $match->filter(Functions::fail());
    }
}

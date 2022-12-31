<?php
namespace Test\Feature\CleanRegex\match\filter;

use PHPUnit\Framework\TestCase;
use Test\Utils\Functions;
use Test\Utils\TestCase\TestCasePasses;
use Test\Utils\TypeFunctions;
use TRegx\CleanRegex\Exception\InvalidReturnValueException;
use TRegx\CleanRegex\Pattern;
use TRegx\Exception\MalformedPatternException;

/**
 * @covers \TRegx\CleanRegex\Match\Search
 */
class SearchTest extends TestCase
{
    use TestCasePasses;

    /**
     * @test
     */
    public function shouldFilter()
    {
        // given
        $search = pattern('\w+')->search('Foo, Bar, Top, Door');
        // when
        $filtered = $search->filter(Functions::oneOf(['Foo', 'Top', 'Door']));
        // then
        $this->assertSame(['Foo', 'Top', 'Door'], $filtered);
    }

    /**
     * @test
     */
    public function shouldFilter_acceptString()
    {
        // given
        $search = pattern('Foo')->search('Foo');
        // when
        $search->filter(TypeFunctions::assertTypeString(true));
        // then
        $this->pass();
    }

    /**
     * @test
     */
    public function shouldThrow_forInvalidReturnType_integer()
    {
        // given
        $search = pattern('Foo')->search('Foo');
        // then
        $this->expectException(InvalidReturnValueException::class);
        $this->expectExceptionMessage('Invalid filter() callback return type. Expected bool, but integer (12) given');
        // when
        $search->filter(Functions::constant(12));
    }

    /**
     * @test
     */
    public function shouldThrow_forInvalidReturnType_array()
    {
        // given
        $search = pattern('Foo')->search('Foo');
        // then
        $this->expectException(InvalidReturnValueException::class);
        $this->expectExceptionMessage('Invalid filter() callback return type. Expected bool, but array (0) given');
        // when
        $search->filter(Functions::constant([]));
    }

    /**
     * @test
     */
    public function shouldThrowForMalformedPattern()
    {
        // given
        $search = Pattern::of('+')->search('Foo');
        // when
        $this->expectException(MalformedPatternException::class);
        $this->expectExceptionMessage('Quantifier does not follow a repeatable item at offset 0');
        // then
        $search->filter(Functions::fail());
    }
}

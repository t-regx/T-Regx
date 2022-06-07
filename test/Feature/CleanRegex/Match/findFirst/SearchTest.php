<?php
namespace Test\Feature\CleanRegex\Match\findFirst;

use PHPUnit\Framework\TestCase;
use Test\Utils\Assertion\AssertsOptional;
use Test\Utils\Classes\ExampleException;
use Test\Utils\Functions;
use Test\Utils\TestCase\TestCasePasses;
use TRegx\CleanRegex\Pattern;
use TRegx\Exception\MalformedPatternException;

/**
 * @covers \TRegx\CleanRegex\Match\Search
 */
class SearchTest extends TestCase
{
    use TestCasePasses, AssertsOptional;

    /**
     * @test
     */
    public function shouldFindFirst()
    {
        // given
        $search = Pattern::literal('evil')->search('Inheritance is evil');
        // when
        $first1 = $search->findFirst()->orReturn(null);
        $first2 = $search->findFirst()->orThrow(new \Exception());
        $first3 = $search->findFirst()->orElse(Functions::fail());
        // then
        $this->assertSame('evil', $first1);
        $this->assertSame('evil', $first2);
        $this->assertSame('evil', $first3);
    }

    /**
     * @test
     */
    public function shouldReturnEmptyOptionalOrElse()
    {
        // given
        $search = Pattern::literal('Foo')->search('Bar');
        // when
        $value = $search->findFirst()->orElse(Functions::constant('other'));
        // then
        $this->assertSame('other', $value);
    }

    /**
     * @test
     */
    public function shouldReturnEmptyOptionalOrReturn()
    {
        // given
        $search = Pattern::literal('Black')->search('White');
        // when
        $value = $search->findFirst()->orReturn('fallback');
        // then
        $this->assertSame('fallback', $value);
    }

    /**
     * @test
     */
    public function shouldReturnEmptyOptionalGet()
    {
        // given
        $search = Pattern::literal('Foo')->search('Bar');
        // when
        $optional = $search->findFirst();
        // then
        $this->assertOptionalEmpty($optional);
    }

    /**
     * @test
     */
    public function shouldReturnEmptyOptionalOrThrowCustomException()
    {
        // given
        $search = Pattern::literal('Foo')->search('Bar');
        // then
        $this->expectException(ExampleException::class);
        // when
        $search->findFirst()->orThrow(new ExampleException());
    }

    /**
     * @test
     */
    public function shouldCallEvenWithoutCollapsingOrMethod()
    {
        // when
        Pattern::literal('Foo')->search('Foo')->findFirst()->map(Functions::pass());
    }

    /**
     * @test
     */
    public function shouldMapOptional()
    {
        // given
        $search = Pattern::of('Foo')->search('Foo');
        // when
        $value = $search->findFirst()->map(Functions::constant('Different'))->orElse(Functions::fail());
        // then
        $this->assertSame('Different', $value);
    }

    /**
     * @test
     */
    public function shouldOrElseReceiveNoArgument()
    {
        // given
        $search = Pattern::of('Foo')->search('Bar');
        // when
        $search->findFirst()->orElse(Functions::assertArgumentless());
    }

    /**
     * @test
     */
    public function shouldNotMapOptionalEmpty()
    {
        // given
        $search = Pattern::of('Foo')->search('Bar');
        // when
        $optional = $search->findFirst()->map(Functions::fail());
        // then
        $this->assertOptionalEmpty($optional);
    }

    /**
     * @test
     */
    public function shouldMapOptionalEmptyOrThrow()
    {
        // then
        $this->expectException(ExampleException::class);
        // when
        Pattern::of('Foo')->search('Bar')->findFirst()->map(Functions::fail())->orThrow(new ExampleException());
    }

    /**
     * @test
     */
    public function shouldThrowForMalformedPattern()
    {
        // given
        $search = Pattern::of('+')->search('Bar');
        // then
        $this->expectException(MalformedPatternException::class);
        $this->expectExceptionMessage('Quantifier does not follow a repeatable item at offset 0');
        // when
        $search->findFirst();
    }
}

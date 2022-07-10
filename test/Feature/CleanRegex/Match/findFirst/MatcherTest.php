<?php
namespace Test\Feature\CleanRegex\Match\findFirst;

use PHPUnit\Framework\TestCase;
use Test\Utils\Assertion\AssertsDetail;
use Test\Utils\Assertion\AssertsOptional;
use Test\Utils\Classes\ExampleException;
use Test\Utils\Functions;
use Test\Utils\TestCase\TestCasePasses;
use TRegx\CleanRegex\Pattern;
use TRegx\Exception\MalformedPatternException;

/**
 * @covers \TRegx\CleanRegex\Match\Matcher
 */
class MatcherTest extends TestCase
{
    use AssertsDetail, TestCasePasses, AssertsOptional;

    /**
     * @test
     */
    public function shouldFindFirst()
    {
        // given
        $matcher = Pattern::literal('evil')->match('Inheritance is evil');
        // when
        $first1 = $matcher->findFirst()->orReturn(null);
        $first2 = $matcher->findFirst()->orThrow(new \Exception());
        $first3 = $matcher->findFirst()->orElse(Functions::fail());
        // then
        $this->assertDetailText('evil', $first1);
        $this->assertDetailText('evil', $first2);
        $this->assertDetailText('evil', $first3);
    }

    /**
     * @test
     */
    public function shouldFindFirstDetail()
    {
        // when
        $detail = Pattern::literal('Foo', 'i')->match('Foo foo FOO')->findFirst()->get();
        // then
        $this->assertDetailIndex(0, $detail);
        $this->assertDetailSubject('Foo foo FOO', $detail);
        $this->assertDetailAll(['Foo', 'foo', 'FOO'], $detail);
    }

    /**
     * @test
     */
    public function shouldReturnEmptyOptionalOrElse()
    {
        // given
        $matcher = Pattern::literal('Foo')->match('Bar');
        // when
        $value = $matcher->findFirst()->orElse(Functions::constant('other'));
        // then
        $this->assertSame('other', $value);
    }

    /**
     * @test
     */
    public function shouldReturnEmptyOptionalOrReturn()
    {
        // given
        $matcher = Pattern::literal('Black')->match('White');
        // when
        $value = $matcher->findFirst()->orReturn('fallback');
        // then
        $this->assertSame('fallback', $value);
    }

    /**
     * @test
     */
    public function shouldReturnEmptyOptionalGet()
    {
        // given
        $matcher = Pattern::literal('Foo')->match('Bar');
        // when
        $optional = $matcher->findFirst();
        // then
        $this->assertOptionalEmpty($optional);
    }

    /**
     * @test
     */
    public function shouldReturnEmptyOptionalOrThrowCustomException()
    {
        // given
        $matcher = Pattern::literal('Foo')->match('Bar');
        // then
        $this->expectException(ExampleException::class);
        // when
        $matcher->findFirst()->orThrow(new ExampleException());
    }

    /**
     * @test
     */
    public function shouldCallEvenWithoutCollapsingOrMethod()
    {
        // given
        $matcher = Pattern::literal('Foo')->match('Foo');
        // when
        $matcher->findFirst()->map(Functions::pass());
    }

    /**
     * @test
     */
    public function shouldMapOptional()
    {
        // given
        $matcher = Pattern::of('Foo')->match('Foo');
        // when
        $value = $matcher->findFirst()->map(Functions::constant('Different'))->orElse(Functions::fail());
        // then
        $this->assertSame('Different', $value);
    }

    /**
     * @test
     */
    public function shouldOrElseReceiveNoArgument()
    {
        // given
        $matcher = Pattern::of('Foo')->match('Bar');
        // when
        $matcher->findFirst()->orElse(Functions::assertArgumentless());
    }

    /**
     * @test
     */
    public function shouldNotMapOptionalEmpty()
    {
        // given
        $matcher = Pattern::of('Foo')->match('Bar');
        // when
        $optional = $matcher->findFirst()->map(Functions::fail());
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
        pattern('Foo')->match('bar')->findFirst()->map(Functions::fail())->orThrow(new ExampleException());
    }

    /**
     * @test
     */
    public function shouldThrowForMalformedPattern()
    {
        // given
        $matcher = Pattern::of('+')->match('Bar');
        // then
        $this->expectException(MalformedPatternException::class);
        $this->expectExceptionMessage('Quantifier does not follow a repeatable item at offset 0');
        // when
        $matcher->findFirst();
    }
}

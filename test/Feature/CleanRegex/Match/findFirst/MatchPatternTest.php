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
 * @covers \TRegx\CleanRegex\Match\MatchPattern
 */
class MatchPatternTest extends TestCase
{
    use AssertsDetail, TestCasePasses, AssertsOptional;

    /**
     * @test
     */
    public function shouldFindFirst()
    {
        // given
        $match = Pattern::literal('evil')->match('Inheritance is evil');
        // when
        $first1 = $match->findFirst()->orReturn(null);
        $first2 = $match->findFirst()->orThrow(new \Exception());
        $first3 = $match->findFirst()->orElse(Functions::fail());
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
        $match = Pattern::literal('Foo')->match('Bar');
        // when
        $value = $match->findFirst()->orElse(Functions::constant('other'));
        // then
        $this->assertSame('other', $value);
    }

    /**
     * @test
     */
    public function shouldReturnEmptyOptionalOrReturn()
    {
        // given
        $match = Pattern::literal('Black')->match('White');
        // when
        $value = $match->findFirst()->orReturn('fallback');
        // then
        $this->assertSame('fallback', $value);
    }

    /**
     * @test
     */
    public function shouldReturnEmptyOptionalGet()
    {
        // given
        $match = Pattern::literal('Foo')->match('Bar');
        // when
        $optional = $match->findFirst();
        // then
        $this->assertOptionalEmpty($optional);
    }

    /**
     * @test
     */
    public function shouldReturnEmptyOptionalOrThrowCustomException()
    {
        // given
        $match = Pattern::literal('Foo')->match('Bar');
        // then
        $this->expectException(ExampleException::class);
        // when
        $match->findFirst()->orThrow(new ExampleException());
    }

    /**
     * @test
     */
    public function shouldCallEvenWithoutCollapsingOrMethod()
    {
        // when
        Pattern::literal('Foo')->match('Foo')->findFirst()->map(Functions::pass());
    }

    /**
     * @test
     */
    public function shouldMapOptional()
    {
        // given
        $match = Pattern::of('Foo')->match('Foo');
        // when
        $value = $match->findFirst()->map(Functions::constant('Different'))->orElse(Functions::fail());
        // then
        $this->assertSame('Different', $value);
    }

    /**
     * @test
     */
    public function shouldOrElseReceiveNoArgument()
    {
        // given
        $match = Pattern::of('Foo')->match('Bar');
        // when
        $match->findFirst()->orElse(Functions::assertArgumentless());
    }

    /**
     * @test
     */
    public function shouldNotMapOptionalEmpty()
    {
        // given
        $match = Pattern::of('Foo')->match('Bar');
        // when
        $optional = $match->findFirst()->map(Functions::fail());
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
        $match = Pattern::of('+')->match('Bar');
        // then
        $this->expectException(MalformedPatternException::class);
        $this->expectExceptionMessage('Quantifier does not follow a repeatable item at offset 0');
        // when
        $match->findFirst();
    }
}

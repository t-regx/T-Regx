<?php
namespace Test\Feature\CleanRegex\Match\findFirst;

use PHPUnit\Framework\TestCase;
use Test\Utils\Assertion\AssertsOptional;
use Test\Utils\Functions;
use TRegx\CleanRegex\Match\Details\Group\Group;
use TRegx\CleanRegex\Pattern;

/**
 * @covers \TRegx\CleanRegex\Match\MatchPattern
 */
class MatchPatternTest extends TestCase
{
    use AssertsOptional;

    /**
     * @test
     */
    public function shouldCallWithDetails()
    {
        // when
        $detail = Pattern::literal('Foo', 'i')
            ->match('Foo foo FOO')
            ->findFirst()
            ->get();
        // then
        $this->assertSame(0, $detail->index());
        $this->assertSame('Foo foo FOO', $detail->subject());
        $this->assertSame(['Foo', 'foo', 'FOO'], $detail->all());
    }

    /**
     * @test
     */
    public function shouldCallEvenWithoutCollapsingOrMethod()
    {
        // when
        $detail = Pattern::literal('Foo')
            ->match('Foo')
            ->findFirst()
            ->get();
        // then
        $this->assertSame('Foo', $detail->subject());
    }

    /**
     * @test
     */
    public function shouldGetFirst()
    {
        // given
        $pattern = Pattern::literal('Foo')->match('Foo');
        // when
        $optional = $pattern->findFirst();
        // then
        $this->assertOptionalIsPresent($optional);
    }

    /**
     * @test
     */
    public function shouldGetFirstDetail()
    {
        // given
        $pattern = Pattern::literal('Foo')->match('Foo');
        // when
        $optional = $pattern->findFirst();
        // then
        $detail = $optional->get();
        $this->assertSame('Foo', $detail->text());
        $this->assertSame(0, $detail->index());
    }

    /**
     * @test
     */
    public function shouldReturnEmptyOptional_onUnmatchingSubject()
    {
        // given
        $pattern = Pattern::literal('Foo')->match('Bar');
        // when
        $optional = $pattern->findFirst('strRev');
        // then
        $this->assertOptionalEmpty($optional);
    }

    /**
     * @test
     */
    public function should_onNotMatchingSubject()
    {
        // given
        $pattern = Pattern::literal('Foo')->match('Bar');
        // when
        $optional = $pattern->findFirst('strRev');
        // then
        $this->assertOptionalEmpty($optional);
    }

    /**
     * @test
     */
    public function should_onNotMatchingSubject_getDefault()
    {
        // given
        $pattern = Pattern::literal('Foo')->match('Bar');
        // when
        $value = $pattern->findFirst()->orReturn('def');
        // then
        $this->assertSame('def', $value);
    }

    /**
     * @test
     */
    public function should_onNotMatchingSubject_call()
    {
        // given
        $pattern = Pattern::literal('Foo')->match('Bar');
        // when
        $value = $pattern->findFirst()->orElse(Functions::constant('new value'));
        // then
        $this->assertSame('new value', $value);
    }

    /**
     * @test
     */
    public function shouldOrElseRecieveNoArguments()
    {
        // given
        $pattern = Pattern::of('Foo')->match('Bar');
        // when
        $pattern->findFirst()->orElse(Functions::assertArgumentless());
    }

    /**
     * @test
     */
    public function shouldSubstituteGroup()
    {
        // given
        $match = Pattern::of('[!?]"(Foo)"')->match('€Subject: !"Foo", ?"Foo"');
        [$first, $second] = $match->map(Functions::identity());
        /** @var Group $group */
        [$group] = $second->groups();
        // when
        $substitute = $group->substitute('Bar');
        // then
        $this->assertSame('?"Bar"', $substitute);
    }

    /**
     * @test
     */
    public function shouldSubstituteGroupFirst()
    {
        // given
        $match = Pattern::of('!"(Foo)"')->match('€Subject: !"Foo"');
        $detail = $match->findFirst()->get();
        [$first] = $detail->groups();
        // when
        $substitute = $first->substitute('Bar');
        // then
        $this->assertSame('!"Bar"', $substitute);
    }
}

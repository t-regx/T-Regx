<?php
namespace Test\Feature\CleanRegex\Match\findFirst;

use PHPUnit\Framework\TestCase;
use Test\Utils\AssertsOptional;
use Test\Utils\DetailFunctions;
use Test\Utils\Functions;
use TRegx\CleanRegex\Exception\EmptyOptionalException;
use TRegx\CleanRegex\Match\Details\Detail;
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
        Pattern::literal('Foo', 'i')
            ->match('Foo foo FOO')
            ->findFirst(function (Detail $detail) {
                // then
                $this->assertSame(0, $detail->index());
                $this->assertSame('Foo foo FOO', $detail->subject());
                $this->assertSame(['Foo', 'foo', 'FOO'], $detail->all());
            })
            ->get();
    }

    /**
     * @test
     */
    public function shouldCallEvenWithoutCollapsingOrMethod()
    {
        // when
        Pattern::literal('Foo')
            ->match('Foo')
            ->findFirst(function (Detail $detail) {
                // then
                $this->assertSame('Foo', $detail->subject());
            });
    }

    /**
     * @test
     */
    public function shouldGetFirst()
    {
        // given
        $pattern = Pattern::literal('Foo')->match('Foo');
        // when
        $optional = $pattern->findFirst('strToUpper');
        // then
        $this->assertOptionalPresent($optional, 'FOO');
    }

    /**
     * @test
     */
    public function shouldNotInvokeFirst_ForUnmatchedSubject()
    {
        // given
        $pattern = Pattern::literal('Foo')->match('Bar');
        // when
        $pattern->findFirst(Functions::fail())->orReturn(null);
        $pattern->findFirst(Functions::fail())->orElse(Functions::pass());
        try {
            $pattern->findFirst(Functions::fail())->get();
        } catch (EmptyOptionalException $ignored) {
        }
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
        $value = $pattern->findFirst('strRev')->orReturn('def');
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
        $value = $pattern->findFirst('strRev')->orElse(Functions::constant('new value'));
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
        $pattern->findFirst(Functions::fail())->orElse(Functions::assertArgumentless());
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
        $match->findFirst(DetailFunctions::out($detail))->get();

        /** @var Group $first */
        [$first] = $detail->groups();
        // when
        $substitute = $first->substitute('Bar');
        // then
        $this->assertSame('!"Bar"', $substitute);
    }
}

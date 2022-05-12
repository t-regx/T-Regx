<?php
namespace Test\Feature\TRegx\CleanRegex\Match\findFirst;

use PHPUnit\Framework\TestCase;
use Test\Utils\ExampleException;
use Test\Utils\Functions;
use TRegx\CleanRegex\Exception\SubjectNotMatchedException;
use TRegx\CleanRegex\Match\Details\Detail;
use TRegx\CleanRegex\Match\Details\NotMatched;
use TRegx\CleanRegex\Pattern;

/**
 * @covers \TRegx\CleanRegex\Match\MatchPattern::findFirst
 */
class MatchPatternTest extends TestCase
{
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
        $first1 = $pattern->findFirst('strToUpper')->orReturn(null);
        $first2 = $pattern->findFirst('strToUpper')->orThrow(new \Exception());
        $first3 = $pattern->findFirst('strToUpper')->orElse(Functions::fail());

        // then
        $this->assertSame('FOO', $first1);
        $this->assertSame('FOO', $first2);
        $this->assertSame('FOO', $first3);
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
        } catch (SubjectNotMatchedException $ignored) {
        }
    }

    /**
     * @test
     */
    public function shouldThrow_orThrow_onNotMatchingSubject_throw()
    {
        // given
        $pattern = Pattern::literal('Foo')->match('Bar');
        // then
        $this->expectException(SubjectNotMatchedException::class);
        $this->expectExceptionMessage('Expected to get the first match, but subject was not matched');
        // when
        $pattern->findFirst('strRev')->get();
    }

    /**
     * @test
     */
    public function should_onNotMatchingSubject()
    {
        // given
        $pattern = Pattern::literal('Foo')->match('Bar');
        // then
        $this->expectException(ExampleException::class);
        // when
        $pattern->findFirst('strRev')->orThrow(new ExampleException());
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
    public function should_onNotMatchingSubject_call_withDetails()
    {
        // given
        $pattern = Pattern::of("(?:[A-Z])?[a-z']+ (?<group>.)")->match('NOT MATCHING');
        // when
        $pattern->findFirst('strRev')->orElse(function (NotMatched $details) {
            // then
            $this->assertSame('NOT MATCHING', $details->subject());
            $this->assertSame(['group'], $details->groupNames());
            $this->assertTrue($details->hasGroup('group'));
            $this->assertTrue($details->hasGroup(0));
            $this->assertTrue($details->hasGroup(1));
            $this->assertFalse($details->hasGroup('other'));
            $this->assertFalse($details->hasGroup(2));
        });
    }
}

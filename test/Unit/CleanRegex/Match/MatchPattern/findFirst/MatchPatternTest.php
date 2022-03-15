<?php
namespace Test\Unit\TRegx\CleanRegex\Match\MatchPattern\findFirst;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Test\Utils\Definitions;
use Test\Utils\Functions;
use TRegx\CleanRegex\Exception\SubjectNotMatchedException;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\CleanRegex\Match\Details\Detail;
use TRegx\CleanRegex\Match\Details\NotMatched;
use TRegx\CleanRegex\Match\MatchPattern;

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
        // given
        $pattern = new MatchPattern(Definitions::pattern('Foo', 'i'), new Subject('Foo foo FOO'));

        // when
        $pattern
            ->findFirst(function (Detail $detail) {
                // then
                $this->assertSame(0, $detail->index());
                $this->assertSame('Foo foo FOO', $detail->subject());
                $this->assertSame(['Foo', 'foo', 'FOO'], $detail->all());
            })
            ->orThrow();
    }

    /**
     * @test
     */
    public function shouldCallEvenWithoutCollapsingOrMethod()
    {
        // given
        $pattern = new MatchPattern(Definitions::pattern('Foo'), new Subject('Foo'));

        // when
        $pattern->findFirst(function (Detail $detail) {
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
        $pattern = new MatchPattern(Definitions::pattern('Foo'), new Subject('Foo'));

        // when
        $first1 = $pattern->findFirst('strToUpper')->orReturn(null);
        $first2 = $pattern->findFirst('strToUpper')->orThrow();
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
        $pattern = new MatchPattern(Definitions::pattern('Foo'), new Subject('Bar'));

        // when
        $pattern->findFirst(Functions::fail())->orReturn(null);
        $pattern->findFirst(Functions::fail())->orElse(Functions::pass());
        try {
            $pattern->findFirst(Functions::fail())->orThrow();
        } catch (SubjectNotMatchedException $ignored) {
        }
    }

    /**
     * @test
     */
    public function shouldThrow_orThrow_onNotMatchingSubject_throw()
    {
        // given
        $pattern = new MatchPattern(Definitions::pattern('Foo'), new Subject('Bar'));

        // then
        $this->expectException(SubjectNotMatchedException::class);
        $this->expectExceptionMessage('Expected to get the first match, but subject was not matched');

        // when
        $pattern->findFirst('strRev')->orThrow();
    }

    /**
     * @test
     */
    public function should_onNotMatchingSubject_throw_userGivenException()
    {
        // given
        $pattern = new MatchPattern(Definitions::pattern('Foo'), new Subject('Bar'));

        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Expected to get the first match, but subject was not matched');

        // when
        $pattern->findFirst('strRev')->orThrow(InvalidArgumentException::class);
    }

    /**
     * @test
     */
    public function should_onNotMatchingSubject_throw_withMessage()
    {
        // given
        $pattern = new MatchPattern(Definitions::pattern('Foo'), new Subject('Bar'));

        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Expected to get the first match, but subject was not matched');

        // when
        $pattern->findFirst('strRev')->orThrow(InvalidArgumentException::class);
    }

    /**
     * @test
     */
    public function should_onNotMatchingSubject_getDefault()
    {
        // given
        $pattern = new MatchPattern(Definitions::pattern('Foo'), new Subject('Bar'));

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
        $pattern = new MatchPattern(Definitions::pattern('Foo'), new Subject('Bar'));

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
        $pattern = new MatchPattern(Definitions::pattern("(?:[A-Z])?[a-z']+ (?<group>.)"), new Subject('NOT MATCHING'));

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

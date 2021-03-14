<?php
namespace Test\Unit\TRegx\CleanRegex\Match\MatchPattern\first;

use PHPUnit\Framework\TestCase;
use Test\Utils\Functions;
use Test\Utils\Internal;
use TRegx\CleanRegex\Exception\SubjectNotMatchedException;
use TRegx\CleanRegex\Match\Details\Detail;
use TRegx\CleanRegex\Match\MatchPattern;

class MatchPatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetFirst()
    {
        // given
        $pattern = $this->getMatchPattern('Nice matching pattern');

        // when
        $first = $pattern->first();

        // then
        $this->assertSame('Nice', $first);
    }

    /**
     * @test
     */
    public function shouldGetFirst_emptyMatch()
    {
        // given
        $pattern = new MatchPattern(Internal::pattern("9?(?=matching)"), 'Nice matching pattern');

        // when
        $first = $pattern->first();

        // then
        $this->assertSame('', $first);
    }

    /**
     * @test
     */
    public function shouldGetFirst_withCallback()
    {
        // given
        $pattern = $this->getMatchPattern('Nice matching pattern');

        // when
        $first = $pattern->first('strRev');

        // then
        $this->assertSame('eciN', $first);
    }

    /**
     * @test
     */
    public function shouldGetMatch_withDetails()
    {
        // given
        $pattern = $this->getMatchPattern('Nice matching pattern');

        // when
        $pattern->first(function (Detail $detail) {
            // then
            $this->assertSame(0, $detail->index());
            $this->assertSame('Nice matching pattern', $detail->subject());
            $this->assertSame(['Nice', 'matching', 'pattern'], $detail->all());
            $this->assertSame(['N'], $detail->groups()->texts());
        });
    }

    /**
     * @test
     */
    public function shouldNotInvokeFirst_onNotMatchingSubject()
    {
        // given
        $pattern = $this->getMatchPattern('NOT MATCHING');

        // then
        $this->expectException(SubjectNotMatchedException::class);
        $this->expectExceptionMessage('Expected to get the first match, but subject was not matched');

        // when
        $pattern->first(Functions::fail());
    }

    /**
     * @test
     */
    public function shouldThrow_onNotMatchingSubject()
    {
        // given
        $pattern = $this->getMatchPattern('NOT MATCHING');

        // then
        $this->expectException(SubjectNotMatchedException::class);
        $this->expectExceptionMessage('Expected to get the first match, but subject was not matched');

        // when
        $pattern->first();
    }

    /**
     * @test
     */
    public function shouldThrow_withCallback_onNotMatchingSubject()
    {
        // given
        $pattern = $this->getMatchPattern('NOT MATCHING');

        // then
        $this->expectException(SubjectNotMatchedException::class);
        $this->expectExceptionMessage('Expected to get the first match, but subject was not matched');

        // when
        $pattern->first('strRev');
    }

    private function getMatchPattern($subject): MatchPattern
    {
        return new MatchPattern(Internal::pattern("([A-Z])?[a-z']+"), $subject);
    }
}

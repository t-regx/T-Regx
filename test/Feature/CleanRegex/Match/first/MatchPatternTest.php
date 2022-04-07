<?php
namespace Test\Feature\TRegx\CleanRegex\Match\first;

use PHPUnit\Framework\TestCase;
use Test\Utils\Definitions;
use Test\Utils\Functions;
use TRegx\CleanRegex\Exception\SubjectNotMatchedException;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\CleanRegex\Match\Details\Detail;
use TRegx\CleanRegex\Match\MatchPattern;
use TRegx\CleanRegex\Pattern;

/**
 * @covers \TRegx\CleanRegex\Match\MatchPattern::first
 */
class MatchPatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetFirst()
    {
        // given
        $pattern = $this->match('Nice matching pattern');
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
        $pattern = new MatchPattern(Definitions::pattern("9?(?=matching)"), new Subject('Nice matching pattern'));
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
        $pattern = $this->match('Nice matching pattern');
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
        $pattern = $this->match('Nice matching pattern');
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
        $pattern = $this->match('NOT MATCHING');
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
        $pattern = $this->match('NOT MATCHING');
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
        $pattern = $this->match('NOT MATCHING');
        // then
        $this->expectException(SubjectNotMatchedException::class);
        $this->expectExceptionMessage('Expected to get the first match, but subject was not matched');
        // when
        $pattern->first('strRev');
    }

    private function match(string $subject): MatchPattern
    {
        return Pattern::of("([A-Z])?[a-z]+")->match($subject);
    }
}

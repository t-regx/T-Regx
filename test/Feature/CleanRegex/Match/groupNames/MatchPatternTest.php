<?php
namespace Test\Feature\CleanRegex\Match\groupNames;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Match\Details\Structure;
use TRegx\CleanRegex\Pattern;

/**
 * @covers \TRegx\CleanRegex\Match\MatchPattern
 */
class MatchPatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldReturnNoNamesForPatternWithoutGroups()
    {
        // given
        $notMatched = $this->notMatched(Pattern::of('Foo'));
        // when
        $this->assertSame([], $notMatched->groupNames());
    }

    /**
     * @test
     */
    public function shouldGetNullNames()
    {
        // given
        $notMatched = $this->notMatched(Pattern::of('(Foo)(1)(2)'));
        // when
        $this->assertSame([null, null, null], $notMatched->groupNames());
    }

    /**
     * @test
     */
    public function shouldGetNames()
    {
        // given
        $notMatched = $this->notMatched(Pattern::of('(?<a>Foo)(?<b>1)(?<c>2)'));
        // when
        $this->assertSame(['a', 'b', 'c'], $notMatched->groupNames());
    }

    /**
     * @test
     */
    public function shouldGetNamesAndNulls()
    {
        // given
        $notMatched = $this->notMatched(Pattern::of('(?<a>Foo)(1)(?<c>2)(3)'));
        // when
        $this->assertSame(['a', null, 'c', null], $notMatched->groupNames());
    }

    /**
     * @test
     */
    public function shouldGetNamesAndNullsNullFirst()
    {
        // given
        $notMatched = $this->notMatched(Pattern::of('()(?<a>1)(2)(?<b>3)'));
        // when
        $this->assertSame([null, 'a', null, 'b'], $notMatched->groupNames());
    }

    /**
     * @test
     * @depends shouldGetNamesAndNullsNullFirst
     */
    public function shouldGetNamesAndNullsNullFirstAlternate()
    {
        // given
        $notMatched = $this->notMatched(Pattern::of('()(?<a>1)(?<b>2)(3)(?<c>4)'));
        // when
        $this->assertSame([null, 'a', 'b', null, 'c'], $notMatched->groupNames());
    }

    private function notMatched(Pattern $pattern): Structure
    {
        return $pattern->match('Bar');
    }
}

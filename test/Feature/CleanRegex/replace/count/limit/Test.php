<?php
namespace Test\Feature\CleanRegex\replace\count\limit;

use PHPUnit\Framework\TestCase;
use Test\Utils\Backtrack\CausesBacktracking;
use TRegx\CleanRegex\Pattern;
use TRegx\Exception\MalformedPatternException;
use TRegx\SafeRegex\Exception\CatastrophicBacktrackingException;

/**
 * @coversNothing
 */
class Test extends TestCase
{
    use CausesBacktracking;

    /**
     * @test
     */
    public function shouldCountFirstUnmatched()
    {
        // given
        $pattern = Pattern::of('Foo');
        // when
        $count = $pattern->replace('failing')->limit(2)->count();
        // then
        $this->assertSame(0, $count);
    }

    /**
     * @test
     */
    public function shouldCountZeroMatchedFirst()
    {
        // given
        $pattern = Pattern::of('\d+');
        // when
        $count = $pattern->replace('12')->limit(0)->count();
        // then
        $this->assertSame(0, $count);
    }

    /**
     * @test
     */
    public function shouldCountFirstMatched()
    {
        // given
        $pattern = Pattern::of('\d+');
        // when
        $count = $pattern->replace('12')->limit(1)->count();
        // then
        $this->assertSame(1, $count);
    }

    /**
     * @test
     */
    public function shouldExactly2CountMatched()
    {
        // given
        $pattern = Pattern::of('\d+');
        // when
        $count = $pattern->replace('12')->limit(2)->count();
        // then
        $this->assertSame(1, $count);
    }

    /**
     * @test
     */
    public function shouldExactly2CountMatchedTwo()
    {
        // given
        $pattern = Pattern::of('\d+');
        // when
        $count = $pattern->replace('12, 12')->limit(2)->count();
        // then
        $this->assertSame(2, $count);
    }

    /**
     * @test
     */
    public function shouldExactly2CountMatchedThree()
    {
        // given
        $pattern = Pattern::of('\d+');
        // when
        $count = $pattern->replace('12, 13, 14')->limit(2)->count();
        // then
        $this->assertSame(2, $count);
    }

    /**
     * @test
     */
    public function shouldThrowMalformedPattern()
    {
        // given
        $pattern = Pattern::of('+');
        // then
        $this->expectException(MalformedPatternException::class);
        $this->expectExceptionMessage('Quantifier does not follow a repeatable item at offset 0');
        // when
        $pattern->replace('Fail')->limit(2)->count();
    }

    /**
     * @test
     */
    public function shouldThrowMalformedPatternExactly0()
    {
        // given
        $pattern = Pattern::of('+');
        // then
        $this->expectException(MalformedPatternException::class);
        $this->expectExceptionMessage('Quantifier does not follow a repeatable item at offset 0');
        // when
        $pattern->replace('Fail')->limit(0)->count();
    }

    /**
     * @test
     */
    public function shouldThrowMalformedPatternTemplate()
    {
        // given
        $pattern = Pattern::of("\\");
        // then
        $this->expectException(MalformedPatternException::class);
        $this->expectExceptionMessage('Pattern may not end with a trailing backslash');
        // when
        $pattern->replace('Fail'); //->limit(1)->count();
    }

    /**
     * @test
     */
    public function shouldThrowCatastrophicBacktracking()
    {
        // then
        $this->expectException(CatastrophicBacktrackingException::class);
        // when
        $this->backtrackingReplace(3)->limit(4)->count();
    }

    /**
     * @test
     */
    public function shouldCountExactly3_CatastrophicBacktrackingAt4()
    {
        // when
        $count = $this->backtrackingReplace(3)->limit(3)->count();
        // then
        $this->assertSame(3, $count);
    }
}

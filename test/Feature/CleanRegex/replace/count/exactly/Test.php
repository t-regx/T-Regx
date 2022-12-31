<?php
namespace Test\Feature\CleanRegex\replace\count\exactly;

use PHPUnit\Framework\TestCase;
use Test\Utils\Backtrack\CausesBacktracking;
use TRegx\CleanRegex\Exception\ReplacementExpectationFailedException;
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
        // then
        $this->expectException(ReplacementExpectationFailedException::class);
        $this->expectExceptionMessage('Expected to perform exactly 2 replacement(s), but 0 replacement(s) were actually performed');
        // when
        $pattern->replace('failing')->exactly(2)->count();
    }

    /**
     * @test
     */
    public function shouldCountFirstMatched()
    {
        // given
        $pattern = Pattern::of('\d+');
        // when
        $count = $pattern->replace('12')->exactly(1)->count();
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
        // then
        $this->expectException(ReplacementExpectationFailedException::class);
        $this->expectExceptionMessage('Expected to perform exactly 2 replacement(s), but 1 replacement(s) were actually performed');
        // when
        $pattern->replace('12')->exactly(2)->count();
    }

    /**
     * @test
     */
    public function shouldExactly2CountMatchedTwo()
    {
        // given
        $pattern = Pattern::of('\d+');
        // when
        $count = $pattern->replace('12, 12')->exactly(2)->count();
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
        // then
        $this->expectException(ReplacementExpectationFailedException::class);
        $this->expectExceptionMessage('Expected to perform exactly 2 replacement(s), but more than 2 replacement(s) would have been performed');
        // when
        $pattern->replace('12, 13, 14')->exactly(2)->count();
    }

    /**
     * @test
     */
    public function shouldThrowMalformedPattern()
    {
        // given
        $pattern = pattern('+');
        // then
        $this->expectException(MalformedPatternException::class);
        $this->expectExceptionMessage('Quantifier does not follow a repeatable item at offset 0');
        // when
        $pattern->replace('Fail')->exactly(2)->count();
    }

    /**
     * @test
     */
    public function shouldThrowMalformedPatternExactly0()
    {
        // given
        $pattern = pattern('+');
        // then
        $this->expectException(MalformedPatternException::class);
        $this->expectExceptionMessage('Quantifier does not follow a repeatable item at offset 0');
        // when
        $pattern->replace('Fail')->exactly(0)->count();
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
        $pattern->replace('Fail');
    }

    /**
     * @test
     */
    public function shouldThrowCatastrophicBacktracking()
    {
        // then
        $this->expectException(CatastrophicBacktrackingException::class);
        // when
        $this->backtrackingReplace(3)->exactly(4)->count();
    }

    /**
     * @test
     */
    public function shouldCountExactly3_CatastrophicBacktrackingAt4()
    {
        // when
        $replace = $this->backtrackingReplace(3)->exactly(3);
        // then
        $this->expectException(CatastrophicBacktrackingException::class);
        // when
        $replace->count();
    }
}

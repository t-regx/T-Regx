<?php
namespace Test\Feature\CleanRegex\replace\count;

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
    public function shouldCount()
    {
        // when
        $count = Pattern::of('Foo')->replace('Bar')->count();
        // then
        $this->assertSame(0, $count);
    }

    /**
     * @test
     */
    public function shouldCountMany()
    {
        // when
        $count = Pattern::of('\d+')->replace('12, 14, 15')->count();
        // then
        $this->assertSame(3, $count);
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
        $pattern->replace('Fail')->count();
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
        $pattern->replace('Fail'); //->count();
    }

    /**
     * @test
     */
    public function shouldThrowCatastrophicBacktracking()
    {
        // then
        $this->expectException(CatastrophicBacktrackingException::class);
        // when
        $this->backtrackingReplace(2)->count();
    }
}

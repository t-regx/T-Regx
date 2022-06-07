<?php
namespace Test\Feature\CleanRegex\Match\groupsCount;

use PHPUnit\Framework\TestCase;
use Test\Utils\Backtrack\CausesBacktracking;
use Test\Utils\TestCase\TestCasePasses;
use TRegx\CleanRegex\Pattern;
use TRegx\Exception\MalformedPatternException;

/**
 * @covers \TRegx\CleanRegex\Match\MatchPattern
 */
class MatchPatternTest extends TestCase
{
    use TestCasePasses, CausesBacktracking;

    /**
     * @test
     */
    public function shouldGetZeroGroups()
    {
        // when
        $count = Pattern::of('Foo')->match('Foo')->groupsCount();
        // then
        $this->assertSame(0, $count);
    }

    /**
     * @test
     */
    public function shouldGetFourGroups()
    {
        // when
        $count = Pattern::of('(Foo)()()()')->match('Foo')->groupsCount();
        // then
        $this->assertSame(4, $count);
    }

    /**
     * @test
     */
    public function shouldGetFourGroupsMixed()
    {
        // when
        $count = Pattern::of('(Foo)(?<name>)()(?<other>)')->match('Foo')->groupsCount();
        // then
        $this->assertSame(4, $count);
    }

    /**
     * @test
     */
    public function shouldGroupsCountNotCauseCatastrophicBacktracking()
    {
        // when
        $this->backtrackingMatch()->groupsCount();
        // then
        $this->pass();
    }

    /**
     * @test
     */
    public function shouldGroupsCountThrowForMalformedElements()
    {
        // given
        $match = Pattern::of('+')->match('Bar');
        // then
        $this->expectException(MalformedPatternException::class);
        $this->expectExceptionMessage('Quantifier does not follow a repeatable item at offset 0');
        // when
        $match->groupsCount();
    }
}

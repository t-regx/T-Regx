<?php
namespace Test\Feature\CleanRegex\Match\groupNames;

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
    public function shouldGetEmpty()
    {
        // when
        $groupNames = Pattern::of('Foo')->match('Foo')->groupNames();
        // then
        $this->assertSame([], $groupNames);
    }

    /**
     * @test
     */
    public function shouldGetGroupNames()
    {
        // when
        $groupNames = Pattern::of('(?<first>first), (?<second>second)')->match('first, second')->groupNames();
        // then
        $this->assertSame(['first', 'second'], $groupNames);
    }

    /**
     * @test
     */
    public function shouldGetUnnamed()
    {
        // when
        $groupNames = Pattern::of('(Foo)')->match('Foo')->groupNames();
        // then
        $this->assertSame([null], $groupNames);
    }

    /**
     * @test
     */
    public function shouldGetMixed()
    {
        // when
        $groupNames = Pattern::of('(?<name>Foo)(Missing)?')->match('Foo')->groupNames();
        // then
        $this->assertSame(['name', null], $groupNames);
    }

    /**
     * @test
     */
    public function shouldGetDuplicateNamed()
    {
        // when
        $groupNames = Pattern::of('(?<name>Foo)(?<name>Foo)', 'J')->match('Foo')->groupNames();
        // then
        $this->assertSame(['name', null], $groupNames);
    }

    /**
     * @test
     */
    public function shouldThrowForMalformedPattern()
    {
        // given
        $match = Pattern::of('+')->match('Bar');
        // then
        $this->expectException(MalformedPatternException::class);
        $this->expectExceptionMessage('Quantifier does not follow a repeatable item at offset 0');
        // when
        $match->groupNames();
    }

    /**
     * @test
     */
    public function shouldNotCauseCatastrophicBacktracking()
    {
        // when
        $this->backtrackingMatch()->groupNames();
        // then
        $this->pass();
    }
}

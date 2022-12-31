<?php
namespace Test\Feature\CleanRegex\match\Detail\groupNames;

use PHPUnit\Framework\TestCase;
use Test\Utils\Backtrack\CausesBacktracking;
use Test\Utils\TestCase\TestCasePasses;
use TRegx\CleanRegex\Pattern;

class DetailTest extends TestCase
{
    use TestCasePasses, CausesBacktracking;

    /**
     * @test
     */
    public function shouldGetGroupNames()
    {
        // when
        $detail = Pattern::of('(?<first>Fili), (?<second>Kili)')->match('Fili, Kili')->first();
        // when
        $groupNames = $detail->groupNames();
        // then
        $this->assertSame(['first', 'second'], $groupNames);
    }

    /**
     * @test
     */
    public function shouldGetGroupNames_Empty()
    {
        // when
        $detail = Pattern::of('At your service')->match('At your service')->first();
        // when
        $groupNames = $detail->groupNames();
        // then
        $this->assertSame([], $groupNames);
    }

    /**
     * @test
     */
    public function shouldGetGroupNames_Unnamed()
    {
        // given
        $detail = Pattern::of('(Foo)')->match('Foo')->first();
        // when
        $groupNames = $detail->groupNames();
        // then
        $this->assertSame([null], $groupNames);
    }

    /**
     * @test
     */
    public function shouldGetGroupNames_NamedAndUnnamed()
    {
        // given
        $detail = Pattern::of('(?<name>Foo)(Missing)?')->match('Foo')->first();
        // when
        $groupNames = $detail->groupNames();
        // then
        $this->assertSame(['name', null], $groupNames);
    }

    /**
     * @test
     */
    public function shouldGroupNamesNotCauseCatastrophicBacktracking()
    {
        // given
        $detail = $this->backtrackingPattern()->match($this->backtrackingSubject(1))->first();
        // when
        $detail->groupNames();
        // then
        $this->pass();
    }
}

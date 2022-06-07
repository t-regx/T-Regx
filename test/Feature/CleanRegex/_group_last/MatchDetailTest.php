<?php
namespace Test\Feature\CleanRegex\_group_last;

use PHPUnit\Framework\TestCase;
use Test\Utils\Assertion\AssertsGroup;
use TRegx\CleanRegex\Pattern;

class MatchDetailTest extends TestCase
{
    use AssertsGroup;

    /**
     * @test
     */
    public function shouldGetGroupsLastMissing()
    {
        // given
        $detail = Pattern::of('!(?<first>one),(?<second>two)!(Bar)?')
            ->match('!one,two!')
            ->first();
        // when
        $groups = $detail->groups();
        // then
        $this->assertGroupTextsOptional(['one', 'two', null], $groups);
        $this->assertGroupIndicesConsequetive($groups);
    }

    /**
     * @test
     */
    public function shouldGetGroupsNamedLastMissing()
    {
        // given
        $detail = Pattern::of('!(?<first>one),(?<second>two)!(?<name>Bar)?')
            ->match('!one,two!')
            ->first();
        // when
        $groups = $detail->groups();
        // then
        $this->assertGroupTextsOptional(['one', 'two', null], $groups);
        $this->assertGroupIndicesConsequetive($groups);
    }

    /**
     * @test
     */
    public function shouldLastGroupExist()
    {
        // given
        $detail = Pattern::of('!(?<first>one),(?<second>two)!(Bar)?')
            ->match('!one,two!')
            ->first();
        // when,  then
        $this->assertTrue($detail->groupExists(3));
        $this->assertFalse($detail->groupExists(4));
    }

    /**
     * @test
     */
    public function shouldLastNamedGroupExist()
    {
        // given
        $detail = Pattern::of('!(?<first>one),(?<second>two)!(?<name>Bar)?')
            ->match('!one,two!')
            ->first();
        // when,  then
        $this->assertTrue($detail->groupExists('name'));
    }
}

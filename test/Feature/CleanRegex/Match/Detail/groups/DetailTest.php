<?php
namespace Test\Feature\CleanRegex\Match\Detail\groups;

use PHPUnit\Framework\TestCase;
use Test\Utils\Assertion\AssertsGroup;
use Test\Utils\Stream\DetailStrategy;
use TRegx\CleanRegex\Pattern;

class DetailTest extends TestCase
{
    use AssertsGroup;

    /**
     * @test
     * @dataProvider details
     */
    public function shouldGetGroups_text(DetailStrategy $strategy)
    {
        // given
        $match = Pattern::of('(Bifur),(Bofur),(Bombur)?', 'i')->match('Bifur,Bofur,');
        $strategy = $strategy->first($match);
        // when
        $groups = $strategy->groups();
        // then
        $this->assertGroupTextsOptional(['Bifur', 'Bofur', null], $groups);
    }

    /**
     * @test
     * @dataProvider details
     */
    public function shouldGetGroups_consequetive(DetailStrategy $strategy)
    {
        // given
        $match = Pattern::of('(Bifur),(Bofur),(Bombur)?', 'i')->match('Bifur,Bofur,');
        $detail = $strategy->first($match);
        // when
        $groups = $detail->groups();
        // then
        $this->assertGroupIndicesConsequetive($groups);
    }

    /**
     * @test
     * @dataProvider details
     */
    public function shouldGetGroups_offset(DetailStrategy $strategy)
    {
        // given
        $match = Pattern::of('(12€)(cm)', 'i')->match('€€ 12€cm');
        $detail = $strategy->first($match);
        // when
        $groups = $detail->groups();
        // then
        $this->assertGroupOffsets([3, 6], $groups);
        $this->assertGroupByteOffsets([7, 12], $groups);
    }

    /**
     * @test
     * @dataProvider details
     */
    public function shouldGetGroups_substitute(DetailStrategy $strategy)
    {
        // given
        $match = Pattern::of('!"(Foo)"')->match('€Subject: !"Foo"');
        [$first] = $strategy->first($match)->groups();
        // when
        $substitute = $first->substitute('Bar');
        // then
        $this->assertSame('!"Bar"', $substitute);
    }

    /**
     * @test
     * @dataProvider details
     */
    public function shouldGetGroups_forUnmatchedGroup(DetailStrategy $strategy)
    {
        // when
        $match = pattern('Foo(Bar)?(Cat)?')->match('Foo');
        $detail = $strategy->first($match);
        // when
        [$first, $second] = $detail->groups();
        // then
        $this->assertGroupNotMatched($first);
        $this->assertGroupNotMatched($second);
    }

    /**
     * @test
     * @dataProvider details
     */
    public function shouldGetGroups_empty(DetailStrategy $strategy)
    {
        // given
        $match = Pattern::of('Foo')->match('Foo');
        $detail = $strategy->first($match);
        // when
        $groups = $detail->groups();
        // then
        $this->assertSame([], $groups);
    }

    /**
     * @test
     * @dataProvider details
     */
    public function shouldGetGroupsNames(DetailStrategy $strategy)
    {
        // given
        $match = pattern('(zero), (?<one>first) and (?<two>second)')->match('zero, first and second');
        $detail = $strategy->first($match);
        // when, then
        $this->assertSame([null, 'one', 'two'], $detail->groupNames());
        $this->assertGroupNames([null, 'one', 'two'], $detail->groups());
    }

    public function details(): array
    {
        return [
            'first()' => [DetailStrategy::useFirst()],
            'map()'   => [DetailStrategy::useMap()],
        ];
    }
}

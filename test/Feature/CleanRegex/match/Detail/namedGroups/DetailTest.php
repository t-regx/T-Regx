<?php
namespace Test\Feature\CleanRegex\match\Detail\namedGroups;

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
        $match = Pattern::of('(?<one>Bifur),(?<two>Bofur),(?<three>Bombur)?', 'i')->match('Bifur,Bofur,');
        $detail = $strategy->first($match);
        // when
        $groups = $detail->namedGroups();
        // then
        $expected = ['one' => 'Bifur', 'two' => 'Bofur', 'three' => null];
        $this->assertGroupTextsOptional($expected, $groups);
    }

    /**
     * @test
     * @dataProvider details
     */
    public function shouldGetGroups_consequetive(DetailStrategy $strategy)
    {
        // given
        $match = Pattern::of('(?<one>Bifur),(?<two>Bofur),(?<three>Bombur)?', 'i')->match('Bifur,Bofur,');
        $detail = $strategy->first($match);
        // when
        $groups = $detail->namedGroups();
        // then
        $this->assertGroupIndices(['one' => 1, 'two' => 2, 'three' => 3], $groups);
    }

    /**
     * @test
     * @dataProvider details
     */
    public function shouldGetGroups_offset(DetailStrategy $strategy)
    {
        // given
        $match = Pattern::of('(?<one>first ę) and (?<two>second)')->match('first ę and second');
        $detail = $strategy->first($match);
        // when
        $groups = $detail->namedGroups();
        // then
        $this->assertGroupOffsets(['one' => 0, 'two' => 12], $groups);
        $this->assertGroupByteOffsets(['one' => 0, 'two' => 13], $groups);
    }

    /**
     * @test
     * @dataProvider details
     */
    public function shouldGetGroups_forUnmatchedGroup(DetailStrategy $strategy)
    {
        // when
        $match = Pattern::of('Foo(?<one>Bar)?(?<two>Cat)?')->match('Foo');
        $detail = $strategy->first($match);
        // when
        $groups = $detail->namedGroups();
        $first = $groups['one'];
        $second = $groups['two'];
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
        $groups = $detail->namedGroups();
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
        $match = Pattern::of('(zero), (?<one>first) and (?<two>second)')->match('zero, first and second');
        $detail = $strategy->first($match);
        // when, then
        $this->assertSame([null, 'one', 'two'], $detail->groupNames());
        $this->assertGroupNames(['one' => 'one', 'two' => 'two'], $detail->namedGroups());
    }

    public function details(): array
    {
        return [
            'first()' => [DetailStrategy::useFirst()],
            'map()'   => [DetailStrategy::useMap()],
        ];
    }
}

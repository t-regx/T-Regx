<?php
namespace Test\Feature\CleanRegex\Match\Details\groups\map;

use PHPUnit\Framework\TestCase;
use Test\Utils\AssertsGroup;
use Test\Utils\DetailFunctions;
use TRegx\CleanRegex\Match\Details\Detail;
use TRegx\CleanRegex\Match\Details\Group\Group;
use TRegx\CleanRegex\Pattern;

class MatchDetailTest extends TestCase
{
    use AssertsGroup;

    /**
     * @test
     */
    public function shouldGetMatchedGroup()
    {
        // when
        $groups = pattern('Foo(Bar)')
            ->match('FooBar')
            ->map(function (Detail $detail) {
                return $detail->groups();
            });
        // when
        [[$first]] = $groups;
        // then
        $this->assertGroupMatched($first);
    }

    /**
     * @test
     */
    public function shouldGetUnmatchedGroup()
    {
        // when
        $groups = pattern('Foo(Bar)?')
            ->match('Foo')
            ->map(function (Detail $detail) {
                return $detail->groups();
            });
        // when
        [[$first]] = $groups;
        // then
        $this->assertGroupNotMatched($first);
    }

    /**
     * @test
     */
    public function shouldGetMatchedGroupEmptyGetTexts()
    {
        // when
        $groups = pattern('([a-z]+)(?:\((\d*)\))?')
            ->match('sin(20) + acos() + tan')
            ->map(function (Detail $detail) {
                return $detail->groups();
            });
        // when
        [$first, $second, $third] = $groups;
        // then
        $this->assertGroupTexts(['sin', '20'], $first);
        $this->assertGroupTexts(['acos', ''], $second);
        $this->assertGroupTextsOptional(['tan', null], $third);
    }

    /**
     * @test
     */
    public function shouldGetMatchedGroupEmptyGetOffsets()
    {
        // when
        $groups = pattern('([a-z]+)(?:\((\d*)\))?')
            ->match('sin(20) + acos() + tan')
            ->map(function (Detail $detail) {
                return $detail->groups();
            });
        // when
        [$first, $second, $third] = $groups;
        // then
        $this->assertGroupOffsets([0, 4], $first);
        $this->assertGroupOffsets([10, 15], $second);
        $this->assertGroupOffsetsOptional([19, null], $third);
    }

    /**
     * @test
     */
    public function shouldGetMatchedGroupEmptyBeConsequetive()
    {
        // when
        $groups = pattern('([a-z]+)(?:\((\d*)\))?')
            ->match('sin(20) + cos() + tan')
            ->map(function (Detail $detail) {
                return $detail->groups();
            });
        // when
        [$first, $second, $third] = $groups;
        // then
        $this->assertGroupIndicesConsequetive($first);
        $this->assertGroupIndicesConsequetive($second);
        $this->assertGroupIndicesConsequetive($third);
    }

    /**
     * @test
     */
    public function shouldSubstituteGroup()
    {
        // given
        $match = Pattern::of('!"(Foo)"')->match('€Subject: !"Foo"');
        $match->map(DetailFunctions::out($detail));
        /** @var Group $first */
        [$first] = $detail->groups();
        // when
        $substitute = $first->substitute('Bar');
        // then
        $this->assertSame('!"Bar"', $substitute);
    }
}

<?php
namespace Test\Feature\TRegx\CleanRegex\Match\group;

use PHPUnit\Framework\TestCase;
use Test\Utils\Functions;
use TRegx\CleanRegex\Exception\NonexistentGroupException;
use TRegx\CleanRegex\Match\Details\Group\MatchGroup;

class MatchPatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGet_all()
    {
        // given
        $subject = 'Computer L Three Four';

        // when
        $groups = pattern('[A-Z](?<lowercase>[a-z]+)?')->match($subject)->group('lowercase')->all();

        // then
        $this->assertEquals(['omputer', null, 'hree', 'our'], $groups);
    }

    /**
     * @test
     */
    public function shouldGet_all_unmatched()
    {
        // given
        $subject = 'NOT MATCHING';

        // when
        $all = pattern('[A-Z](?<lowercase>[a-z]+)')->match($subject)->group('lowercase')->all();

        // then
        $this->assertEmpty($all);
    }

    /**
     * @test
     */
    public function shouldGet_onlyOne_unmatched()
    {
        // given
        $subject = 'NOT MATCHING';

        // when
        $all = pattern('[A-Z](?<lowercase>[a-z]+)')->match($subject)->group('lowercase')->only(1);

        // then
        $this->assertEmpty($all);
    }

    /**
     * @test
     */
    public function shouldThrow_all_nonexistent()
    {
        // then
        $this->expectException(NonexistentGroupException::class);
        $this->expectExceptionMessage("Nonexistent group: 'missing'");

        // when
        pattern('[A-Z](?<lowercase>[a-z]+)?')->match('L Three Four')->group('missing')->all();
    }

    /**
     * @test
     */
    public function shouldThrow_fluent_nonexistent()
    {
        // then
        $this->expectException(NonexistentGroupException::class);
        $this->expectExceptionMessage("Nonexistent group: 'missing'");

        // when
        pattern('[A-Z](?<lowercase>[a-z]+)?')->match('L Three Four')->group('missing')->fluent()->all();
    }

    /**
     * @test
     */
    public function shouldThrow_first_nonexistent()
    {
        // then
        $this->expectException(NonexistentGroupException::class);
        $this->expectExceptionMessage("Nonexistent group: 'missing'");

        // when
        pattern('[A-Z](?<lowercase>[a-z]+)?')->match('L Three Four')->group('missing')->first();
    }

    /**
     * @test
     */
    public function shouldThrow_first_consumer_nonexistent()
    {
        // then
        $this->expectException(NonexistentGroupException::class);
        $this->expectExceptionMessage("Nonexistent group: 'missing'");

        // when
        pattern('[A-Z](?<lowercase>[a-z]+)?')->match('L Three Four')->group('missing')->findFirst(Functions::fail());
    }

    /**
     * @test
     */
    public function shouldThrow_onlyOne_nonexistent()
    {
        // then
        $this->expectException(NonexistentGroupException::class);
        $this->expectExceptionMessage("Nonexistent group: 'missing'");

        // when
        pattern('[A-Z](?<lowercase>[a-z]+)?')->match('L Three Four')->group('missing')->only(1);
    }

    /**
     * @test
     */
    public function shouldGet_only()
    {
        // given
        $subject = 'D Computer';

        // when
        $groups1 = pattern('[A-Z](?<lowercase>[a-z]+)?')->match($subject)->group('lowercase')->only(1);
        $groups2 = pattern('[A-Z](?<lowercase>[a-z]+)?')->match($subject)->group('lowercase')->only(2);

        // then
        $this->assertEquals([null], $groups1);
        $this->assertEquals([null, 'omputer'], $groups2);
    }

    /**
     * @test
     */
    public function shouldMap()
    {
        // when
        $groups = pattern('[A-Z](?<lowercase>[a-z]+)?')
            ->match('D Computer')
            ->group('lowercase')
            ->map(function (MatchGroup $group) {
                if ($group->matched()) {
                    return $group->text();
                }
                return "unmatched";
            });

        // then
        $this->assertEquals(['unmatched', 'omputer'], $groups);
    }

    /**
     * @test
     */
    public function shouldFilter()
    {
        // when
        $groups = pattern('\d+(?<unit>kg|[cm]?m)')
            ->match('15mm 12kg 16m 17cm 27kg')
            ->group('unit')
            ->filter(function (MatchGroup $group) {
                return $group->text() !== "kg";
            });

        // then
        $this->assertEquals(['mm', 'm', 'cm'], $groups);
    }

    /**
     * @test
     */
    public function shouldFilter_fluent()
    {
        // when
        $groups = pattern('\d+(?<unit>kg|[cm]?m)')
            ->match('15mm 12kg 16m 17cm 27kg')
            ->group('unit')
            ->fluent()
            ->filter(function (MatchGroup $group) {
                return $group->text() !== "kg";
            })
            ->all();

        // then
        $this->assertEquals(['mm', 'm', 'cm'], $groups);
    }

    /**
     * @test
     */
    public function shouldMap_fluent()
    {
        // when
        $groups = pattern('[A-Z](?<lowercase>[a-z]+)?')
            ->match('D Computer')
            ->group('lowercase')
            ->fluent()
            ->map(function (MatchGroup $group) {
                if ($group->matched()) {
                    return $group->text();
                }
                return "unmatched";
            })
            ->all();

        // then
        $this->assertEquals(['unmatched', 'omputer'], $groups);
    }

    /**
     * @test
     */
    public function shouldGet_offsets()
    {
        // given
        $offsets = pattern('[A-Z](?<lowercase>[a-z]+)?')
            ->match('xd Computer L Three Four')
            ->group('lowercase')
            ->offsets();

        // when
        $only1 = $offsets->only(1);
        $only2 = $offsets->only(2);
        $all = $offsets->all();

        // then
        $this->assertEquals([4], $only1);
        $this->assertEquals([4, null], $only2);
        $this->assertEquals([4, null, 15, 21], $all);
    }

    /**
     * @test
     */
    public function shouldGet_offsets_onlyOne_null()
    {
        // given
        $offsets = pattern('[A-Z](?<lowercase>[a-z]+)?')
            ->match('xd L Three Four')
            ->group('lowercase')
            ->offsets();

        // when
        $only1 = $offsets->only(1);

        // then
        $this->assertEquals([null], $only1);
    }
}

<?php
namespace Test\Feature\CleanRegex\Match\_stream_first;

use PHPUnit\Framework\TestCase;
use Test\Utils\Backtrack\CausesBacktracking;
use Test\Utils\DetailFunctions;
use Test\Utils\Functions;
use TRegx\CleanRegex\Match\MatchPattern;
use TRegx\SafeRegex\Exception\CatastrophicBacktrackingException;

/**
 * @coversNothing
 */
class MatchPatternTest extends TestCase
{
    use CausesBacktracking;

    private function match(): MatchPattern
    {
        return $this->backtrackingMatch();
    }

    /**
     * @test
     */
    public function shouldThrowBacktrackingLimitError_forAll()
    {
        // then
        $this->expectException(CatastrophicBacktrackingException::class);
        // when
        $this->match()->all();
    }

    public function test_first()
    {
        $this->assertSame('123', $this->match()->first());
        $this->assertSame('123', $this->match()->stream()->first()->text());
    }

    public function test_asInt_first()
    {
        $this->assertSame(123, $this->match()->asInt()->first());
        $this->assertSame(123, $this->match()->stream()->asInt()->first());
    }

    public function test_asInt_group_first()
    {
        $this->assertSame(123, $this->match()->group(0)->asInt()->first());
        $this->assertSame(123, $this->match()->group(0)->stream()->asInt()->first());
    }

    public function test_distinct_first()
    {
        $this->assertSame('123', $this->match()->stream()->distinct()->first()->text());
    }

    public function test_values_first()
    {
        $this->assertSame('123', $this->match()->stream()->values()->first()->text());
    }

    public function test_keys_first()
    {
        $this->assertSame(0, $this->match()->stream()->keys()->first());
    }

    public function test_stream_map()
    {
        $this->assertSame('123', $this->match()->stream()->map(Functions::identity())->first()->text());
    }

    public function test_stream_mapEntries()
    {
        $this->assertSame('123', $this->match()->stream()->mapEntries(Functions::secondArgument())->first()->text());
    }

    public function test_stream_groupByCallback()
    {
        $this->assertSame('123', $this->match()->stream()->groupByCallback(Functions::identity())->first()->text());
    }

    public function test_stream_groupByCallback_keys()
    {
        $this->assertSame('123', $this->match()->stream()->groupByCallback(Functions::identity())->keys()->first());
    }

    public function test_findFirst()
    {
        $this->assertSame('123', $this->match()->findFirst(Functions::identity())->get()->text());
    }

    public function test_stream_findFirst()
    {
        $this->assertSame('123', $this->match()->stream()->findFirst(Functions::identity())->get()->text());
    }

    public function test_stream_flatMap()
    {
        // when
        $first = $this->match()->stream()->flatMap(DetailFunctions::duplicate())->first();
        // then
        $this->assertSame('123', $first);
    }

    public function test_stream_filter()
    {
        $this->assertSame('123', $this->match()->stream()->filter(Functions::constant(true))->first()->text());
    }

    public function test_stream_filter_keys()
    {
        $this->assertSame(0, $this->match()->stream()->filter(Functions::constant(true))->keys()->first());
    }

    public function test_groups()
    {
        $this->assertSame('123', $this->match()->group(0)->first());
        $this->assertSame('123', $this->match()->group(0)->stream()->first()->text());
    }

    public function test_groups_text()
    {
        // given
        $this->match()->first(DetailFunctions::out($detail));
        // when
        [$group] = $detail->groups();
        // then
        $this->assertSame('12', $group->text());
    }
}

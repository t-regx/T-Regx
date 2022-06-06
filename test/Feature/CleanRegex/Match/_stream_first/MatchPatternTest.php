<?php
namespace Test\Feature\CleanRegex\Match\_stream_first;

use PHPUnit\Framework\TestCase;
use Test\Utils\CausesBacktracking;
use Test\Utils\DetailFunctions;
use Test\Utils\Functions;
use TRegx\CleanRegex\Match\Details\Detail;
use TRegx\CleanRegex\Match\MatchPattern;
use TRegx\SafeRegex\Exception\CatastrophicBacktrackingException;

/**
 * There are cases, when calling `preg_match()` on a subject is safe, but calling
 * `preg_match_all()` digs too deep and causes backtracking limit exception.
 *
 * That's why it's important, so that `first()`/`findFirst()` method, chain arbitrarily
 * long call `preg_match()` in the end.
 *
 * @coversNothing
 */
class MatchPatternTest extends TestCase
{
    use CausesBacktracking;

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
        // then
        $this->assertSame('123', $this->match()->first());
        $this->assertSame('123', $this->match()->stream()->first()->text());
    }

    public function test_asInt_first()
    {
        // then
        $this->assertSame(123, $this->match()->asInt()->first());
        $this->assertSame(123, $this->match()->stream()->asInt()->first());
    }

    public function test_asInt_group_first()
    {
        // then
        $this->assertSame(123, $this->match()->group(0)->asInt()->first());
        $this->assertSame(123, $this->match()->group(0)->stream()->asInt()->first());
    }

    public function test_distinct_first()
    {
        // then
        $this->assertSame('123', $this->match()->stream()->distinct()->first()->text());
    }

    public function test_values_first()
    {
        // then
        $this->assertSame('123', $this->match()->stream()->values()->first()->text());
    }

    public function test_keys_first()
    {
        // then
        $this->assertSame(0, $this->match()->stream()->keys()->first());
    }

    public function test_stream_map()
    {
        // then
        $this->assertSame('123', $this->match()->stream()->map(Functions::identity())->first()->text());
    }

    public function test_stream_groupByCallback()
    {
        // then
        $this->assertSame('123', $this->match()->stream()->groupByCallback(Functions::identity())->first()->text());
    }

    public function test_stream_groupByCallback_keys()
    {
        // then
        $this->assertSame('123', $this->match()->stream()->groupByCallback(Functions::identity())->keys()->first());
    }

    public function test_findFirst()
    {
        // then
        $this->assertSame('123', $this->match()->findFirst(Functions::identity())->get()->text());
    }

    public function test_stream_findFirst()
    {
        // then
        $this->assertSame('123', $this->match()->stream()->findFirst(Functions::identity())->get()->text());
    }

    public function test_stream_flatMap()
    {
        // when
        $first = $this->match()->stream()
            ->flatMap(function (Detail $a) {
                return [$a->text(), $a->text()];
            })
            ->first();

        // then
        $this->assertSame('123', $first);
    }

    public function test_stream_filter()
    {
        // then
        $this->assertSame('123', $this->match()->stream()->filter(Functions::constant(true))->first()->text());
    }

    public function test_stream_filter_keys()
    {
        // then
        $this->assertSame(0, $this->match()->stream()->filter(Functions::constant(true))->keys()->first());
    }

    public function test_groups()
    {
        $this->assertSame('123', $this->match()->group(0)->first());
        $this->assertSame('123', $this->match()->group(0)->stream()->first()->text());
    }

    public function test_groups_text()
    {
        $this->match()->first(DetailFunctions::out($detail));
        [$group] = $detail->groups();
        $this->assertSame('12', $group->text());
    }

    private function match(): MatchPattern
    {
        return $this->backtrackingMatch();
    }
}

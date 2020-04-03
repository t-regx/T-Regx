<?php
namespace Test\Feature\TRegx\CleanRegex\Match\_stream_first;

use PHPUnit\Framework\TestCase;
use Test\Utils\Functions;
use TRegx\CleanRegex\Match\MatchPattern;
use TRegx\SafeRegex\Exception\BacktrackLimitPregException;

/**
 * There are cases, when calling `preg_match()` on a subject is safe, but calling
 * `preg_match_all()` digs too deep and causes backtracking limit exception.
 *
 * That's why it's important, so that `first()`/`findFirst()` method, chain arbitrarily
 * long call `preg_match()` in the end.
 */
class AbstractMatchPatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldThrowBacktrackingLimitError_forAll()
    {
        // then
        $this->expectException(BacktrackLimitPregException::class);

        // when
        $this->match()->all();
    }

    public function test_first()
    {
        // then
        $this->assertEquals('123', $this->match()->first());
        $this->assertEquals('123', $this->match()->fluent()->first());
    }

    public function test_asInt_first()
    {
        // then
        $this->assertSame(123, $this->match()->asInt()->first());
        $this->assertSame(123, $this->match()->fluent()->asInt()->first());
    }

    public function test_distinct_first()
    {
        // then
        $this->assertEquals('123', $this->match()->fluent()->distinct()->first());
    }

    public function test_values_first()
    {
        // then
        $this->assertEquals('123', $this->match()->fluent()->values()->first());
    }

    public function test_keys_first()
    {
        // then
        $this->assertEquals(0, $this->match()->fluent()->keys()->first());
    }

    public function test_fluent_map()
    {
        // then
        $this->assertEquals('123', $this->match()->fluent()->map(Functions::identity())->first());
    }

    public function test_fluent_groupByCallback()
    {
        // then
        $this->assertEquals('123', $this->match()->fluent()->groupByCallback(Functions::identity())->first());
    }

    public function test_fluent_groupByCallback_keys()
    {
        // then
        $this->assertEquals('123', $this->match()->fluent()->groupByCallback(Functions::identity())->keys()->first());
    }

    public function test_findFirst()
    {
        // then
        $this->assertEquals('123', $this->match()->findFirst(Functions::identity())->orThrow());
    }

    public function test_fluent_findFirst()
    {
        // then
        $this->assertEquals('123', $this->match()->fluent()->findFirst(Functions::identity())->orThrow());
    }

    public function test_fluent_flatMap()
    {
        // when
        $first = $this->match()->fluent()
            ->flatMap(function ($a) {
                return [$a];
            })
            ->first();
        // then
        $this->assertEquals(['123'], $first);
    }

    public function test_groups_and_offsets()
    {
        $this->assertSame(2, $this->match()->offsets()->first());
        $this->assertSame('123', $this->match()->group(0)->first());
        $this->assertSame(2, $this->match()->group(0)->offsets()->first());
        $this->assertSame(2, $this->match()->offsets()->first());

        $this->assertSame(2, $this->match()->offsets()->fluent()->first());
        $this->assertEquals('123', $this->match()->group(0)->fluent()->first());
        $this->assertSame(0, $this->match()->offsets()->fluent()->keys()->first());
        $this->assertSame(2, $this->match()->group(0)->offsets()->fluent()->first());
    }

    private function match(): MatchPattern
    {
        return pattern("((\w+\w+)+3)")->match('  123 aaaaaaaaaaaaaaaaaaaa 3');
    }
}

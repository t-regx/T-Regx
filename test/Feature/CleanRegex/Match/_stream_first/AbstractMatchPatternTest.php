<?php
namespace Test\Feature\TRegx\CleanRegex\Match\_stream_first;

use PHPUnit\Framework\TestCase;
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
class AbstractMatchPatternTest extends TestCase
{
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
        $this->assertSame('123', $this->match()->fluent()->first()->text());
    }

    public function test_asInt_first()
    {
        // then
        $this->assertSame(123, $this->match()->asInt()->first());
        $this->assertSame(123, $this->match()->fluent()->asInt()->first());
    }

    public function test_asInt_group_first()
    {
        // then
        $this->assertSame(123, $this->match()->group(0)->asInt()->first());
        $this->assertSame(123, $this->match()->group(0)->fluent()->asInt()->first());
    }

    public function test_distinct_first()
    {
        // then
        $this->assertSame('123', $this->match()->fluent()->distinct()->first()->text());
    }

    public function test_values_first()
    {
        // then
        $this->assertSame('123', $this->match()->fluent()->values()->first()->text());
    }

    public function test_keys_first()
    {
        // then
        $this->assertSame(0, $this->match()->fluent()->keys()->first());
    }

    public function test_fluent_map()
    {
        // then
        $this->assertSame('123', $this->match()->fluent()->map(Functions::identity())->first()->text());
    }

    public function test_fluent_groupByCallback()
    {
        // then
        $this->assertSame('123', $this->match()->fluent()->groupByCallback(Functions::identity())->first()->text());
    }

    public function test_fluent_groupByCallback_keys()
    {
        // then
        $this->assertSame('123', $this->match()->fluent()->groupByCallback(Functions::identity())->keys()->first());
    }

    public function test_findFirst()
    {
        // then
        $this->assertSame('123', $this->match()->findFirst(Functions::identity())->orThrow()->text());
    }

    public function test_fluent_findFirst()
    {
        // then
        $this->assertSame('123', $this->match()->fluent()->findFirst(Functions::identity())->orThrow()->text());
    }

    public function test_fluent_flatMap()
    {
        // when
        $first = $this->match()->fluent()
            ->flatMap(function (Detail $a) {
                return [$a->text(), $a->text()];
            })
            ->first();

        // then
        $this->assertSame('123', $first);
    }

    public function test_fluent_filter()
    {
        // then
        $this->assertSame('123', $this->match()->fluent()->filter(Functions::constant(true))->first()->text());
    }

    public function test_fluent_filter_keys()
    {
        // then
        $this->assertSame(0, $this->match()->fluent()->filter(Functions::constant(true))->keys()->first());
    }

    public function test_groups_and_offsets()
    {
        $this->assertSame(2, $this->match()->offsets()->first());
        $this->assertSame('123', $this->match()->group(0)->first());
        $this->assertSame(2, $this->match()->group(0)->offsets()->first());
        $this->assertSame(2, $this->match()->offsets()->first());

        $this->assertSame(2, $this->match()->offsets()->first());
        $this->assertSame('123', $this->match()->group(0)->fluent()->first()->text());
        $this->assertSame(0, $this->match()->offsets()->keys()->first());
        $this->assertSame(2, $this->match()->group(0)->offsets()->first());
    }

    private function match(): MatchPattern
    {
        /**
         * This pattern and subject are deliberately created to
         * produce {@see CatastrophicBacktrackingException}, if they
         * are called more than once. That way, we can test
         * whether "first" method really tries to search the first
         * occurrance.
         */
        return pattern("(([a\d]+[a\d]+)+3)")->match('  123 aaaaaaaaaaaaaaaaaaaa 3');
    }
}

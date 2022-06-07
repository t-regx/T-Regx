<?php
namespace Test\Feature\CleanRegex\Match\_stream_first;

use PHPUnit\Framework\TestCase;
use Test\Utils\Backtrack\CausesBacktracking;
use Test\Utils\Functions;
use TRegx\CleanRegex\Match\Search;
use TRegx\SafeRegex\Exception\CatastrophicBacktrackingException;

/**
 * @coversNothing
 */
class SearchTest extends TestCase
{
    use CausesBacktracking;

    private function search(): Search
    {
        return $this->backtrackingSearch();
    }

    /**
     * @test
     */
    public function shouldThrowBacktrackingLimitError_forAll()
    {
        // then
        $this->expectException(CatastrophicBacktrackingException::class);
        // when
        $this->search()->all();
    }

    public function test_first()
    {
        $this->assertSame('123', $this->search()->first());
        $this->assertSame('123', $this->search()->stream()->first());
    }

    public function test_asInt_first()
    {
        $this->assertSame(123, $this->search()->stream()->asInt()->first());
    }

    public function test_distinct_first()
    {
        $this->assertSame('123', $this->search()->stream()->distinct()->first());
    }

    public function test_values_first()
    {
        $this->assertSame('123', $this->search()->stream()->values()->first());
    }

    public function test_keys_first()
    {
        $this->assertSame(0, $this->search()->stream()->keys()->first());
    }

    public function test_stream_map()
    {
        $this->assertSame('123', $this->search()->stream()->map(Functions::identity())->first());
    }

    public function test_stream_groupByCallback()
    {
        $this->assertSame('123', $this->search()->stream()->groupByCallback(Functions::identity())->first());
    }

    public function test_stream_groupByCallback_keys()
    {
        $this->assertSame('123', $this->search()->stream()->groupByCallback(Functions::identity())->keys()->first());
    }

    public function test_findFirst()
    {
        $this->assertSame('123', $this->search()->findFirst()->get());
    }

    public function test_stream_findFirst()
    {
        $this->assertSame('123', $this->search()->stream()->findFirst()->get());
    }

    public function test_stream_flatMap()
    {
        $this->assertSame('123', $this->search()->stream()->flatMap(Functions::duplicate())->first());
    }

    public function test_stream_filter()
    {
        $this->assertSame('123', $this->search()->stream()->filter(Functions::constant(true))->first());
    }

    public function test_stream_filter_keys()
    {
        $this->assertSame(0, $this->search()->stream()->filter(Functions::constant(true))->keys()->first());
    }
}

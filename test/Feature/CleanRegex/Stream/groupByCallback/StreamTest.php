<?php
namespace Test\Feature\CleanRegex\Stream\groupByCallback;

use PHPUnit\Framework\TestCase;
use Test\Utils\Functions;
use Test\Utils\Stream\ArrayStream;

/**
 * @covers \TRegx\CleanRegex\Match\Stream
 * @covers \TRegx\CleanRegex\Internal\Match\Stream\GroupByCallbackStream
 */
class StreamTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGroupBy()
    {
        // given
        $stream = ArrayStream::of(['Father', 'Mother', 'Maiden', 'Crone', 'Warrior', 'Smith', 'Stranger']);
        // when
        $result = $stream->groupByCallback(Functions::charAt(0));
        // then
        $expected = [
            'F' => ['Father'],
            'M' => ['Mother', 'Maiden'],
            'C' => ['Crone'],
            'W' => ['Warrior'],
            'S' => ['Smith', 'Stranger'],
        ];
        $this->assertSame($expected, $result->all());
    }

    /**
     * @test
     * @depends shouldGroupBy
     */
    public function shouldPreserveOrderOfTheFirst()
    {
        // given
        $stream = ArrayStream::of(['Alpha', 'Bravo', 'Charlie', 'Beaver', 'Axe']);
        // when
        $result = $stream->groupByCallback(Functions::charAt(0));
        // then
        $expected = [
            'A' => ['Alpha', 'Axe'],
            'B' => ['Bravo', 'Beaver'],
            'C' => ['Charlie'],
        ];
        $this->assertSame($expected, $result->all());
    }

    /**
     * @test
     */
    public function shouldGroupBy_keys()
    {
        // given
        $stream = ArrayStream::of(['Father', 'Mother', 'Maiden', 'Crone', 'Warrior', 'Smith', 'Stranger']);
        // when
        $keys = $stream->groupByCallback(Functions::charAt(0))->keys()->all();
        // then
        $this->assertSame(['F', 'M', 'C', 'W', 'S'], $keys);
    }

    /**
     * @test
     */
    public function shouldGroupBy_keys_first()
    {
        // given
        $stream = ArrayStream::of(['Father', 'Mother', 'Maiden', 'Crone', 'Warrior', 'Smith', 'Stranger']);
        // when
        $key = $stream->groupByCallback(Functions::charAt(0))->keys()->first();
        // then
        $this->assertSame('F', $key);
    }

    /**
     * @test
     */
    public function shouldGroupIntegerValues()
    {
        // given
        $stream = ArrayStream::of([12, 14, 0, 15, 0]);
        // when
        $result = $stream
            ->groupByCallback(Functions::identity())
            ->all();
        // then
        $expected = [
            12 => [12],
            14 => [14],
            0  => [0, 0],
            15 => [15],
        ];
        $this->assertSame($expected, $result);
    }

    /**
     * @test
     */
    public function shouldGroupByIntegerValues()
    {
        // given
        $stream = ArrayStream::of(['12cm', '14mm', '24cm', '19cm', '12mm', '24mm']);
        // when
        $result = $stream
            ->groupByCallback(Functions::substring(0, 2))
            ->all();
        // then
        $expected = [
            12 => ['12cm', '12mm'],
            14 => ['14mm'],
            24 => ['24cm', '24mm'],
            19 => ['19cm'],
        ];
        $this->assertSame($expected, $result);
    }
}

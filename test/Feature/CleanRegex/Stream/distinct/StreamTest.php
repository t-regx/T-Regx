<?php
namespace Test\Feature\CleanRegex\Stream\distinct;

use PHPUnit\Framework\TestCase;
use Test\Utils\ArrayStream;

/**
 * @covers \TRegx\CleanRegex\Match\Stream
 * @covers \TRegx\CleanRegex\Internal\Match\Stream\UniqueStream
 */
class StreamTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetDistinctValues()
    {
        // given
        $stream = ArrayStream::of(['One', 'Two', 'One', 'Three', 'Two', 'Four']);
        // when
        $distinct = $stream->distinct();
        // then
        $expected = [
            0 => 'One',
            1 => 'Two',
            3 => 'Three',
            5 => 'Four'
        ];
        $this->assertSame($expected, $distinct->all());
    }

    /**
     * @test
     */
    public function shouldGetDistinctValue()
    {
        // given
        $stream = ArrayStream::of(['One', 'Two', 'One', 'Two']);
        // when
        $distinct = $stream->distinct();
        // then
        $this->assertSame('One', $distinct->first());
    }

    /**
     * @test
     */
    public function shouldGetDistinctValueKey()
    {
        // given
        $stream = ArrayStream::of(['uno' => 'One', 'dos' => 'Two', 'One', 'Two']);
        // when
        $distinct = $stream->distinct();
        // then
        $this->assertSame('uno', $distinct->keys()->first());
    }

    /**
     * @test
     * @dataProvider distinctValues
     */
    public function shouldNotMistakeValues(array $distinctValues)
    {
        // given
        $stream = ArrayStream::of($distinctValues);
        // when
        $distinct = $stream->distinct()->all();
        // then
        $this->assertSame($distinctValues, $distinct);
    }

    public function distinctValues(): array
    {
        return [
            [[false, 0]],
            [[null, '']],
            [['', false]],
            [['0', false]],
            [['0', 0]],
            [['12', 12]],
            [['1', true]],
            [[1, true]],
        ];
    }
}

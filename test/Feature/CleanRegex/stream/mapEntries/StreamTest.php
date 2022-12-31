<?php
namespace Test\Feature\CleanRegex\stream\mapEntries;

use PHPUnit\Framework\TestCase;
use Test\Utils\Functions;
use Test\Utils\Stream\ArrayStream;
use Test\Utils\TestCase\TestCasePasses;

/**
 * @covers \TRegx\CleanRegex\Match\Stream
 * @covers \TRegx\CleanRegex\Internal\Match\Stream\MapEntriesStream
 */
class StreamTest extends TestCase
{
    use TestCasePasses;

    /**
     * @test
     */
    public function shouldMap()
    {
        // given
        $stream = ArrayStream::of(['Neo', 'Morpheus', 'Trinity']);
        // when
        $mapped = $stream->mapEntries(Functions::skipArgument(Functions::toUpper()))->all();
        // then
        $this->assertSame(['NEO', 'MORPHEUS', 'TRINITY'], $mapped);
    }

    /**
     * @test
     */
    public function shouldMapFirst()
    {
        // given
        $stream = ArrayStream::of(['Neo', 'Morpheus', 'Trinity']);
        // when
        $mapped = $stream->mapEntries(Functions::skipArgument(Functions::toUpper()))->first();
        // then
        $this->assertSame('NEO', $mapped);
    }

    /**
     * @test
     */
    public function shouldInvokeMapper_forFirst()
    {
        // guveb
        $stream = ArrayStream::of(['One', 'Two', 'Three']);
        // when, then
        $stream->mapEntries(Functions::assertArguments(0, 'One'))->first();
    }

    /**
     * @test
     */
    public function shouldInvokeMapper_forFirst_once()
    {
        // given
        $stream = ArrayStream::of(['One', 'Two', 'Three']);
        // when
        $stream->mapEntries(Functions::once())->first();
        // then
        $this->pass();
    }

    /**
     * @test
     */
    public function shouldMapToInteger()
    {
        // given
        $stream = ArrayStream::of(['10', '15', '18']);
        // when
        $integers = $stream->mapEntries(Functions::skipArgument(Functions::toInt()))->all();
        // then
        $this->assertSame([10, 15, 18], $integers);
    }

    /**
     * @test
     */
    public function shouldMapToInteger_forFirst()
    {
        // given
        $stream = ArrayStream::of(['10', 'foo', 'bar']);
        // when
        $integer = $stream->mapEntries(Functions::skipArgument(Functions::toInt()))->first();
        // then
        $this->assertSame(10, $integer);
    }

    /**
     * @test
     */
    public function shouldInvokeMapper_forFirst_keys()
    {
        // given
        $stream = ArrayStream::of(['one' => 'Neo', 'Trinity', 'Morpheus']);
        // when
        $stream->mapEntries(Functions::assertArguments('one', 'Neo'))->keys()->first();
    }

    /**
     * @test
     */
    public function shouldInvokeMapper_forFirst_keys_once()
    {
        // given
        $stream = ArrayStream::of(['Neo', 'Trinity', 'Morpheus']);
        // when
        $stream->mapEntries(Functions::once())->keys()->first();
        // then
        $this->pass();
    }

    /**
     * @test
     */
    public function shouldCallFlatMapOnce()
    {
        // given
        $stream = ArrayStream::of(['Say hello to my little friend']);
        // when
        $stream->flatMap(Functions::once(['key']))
            ->mapEntries(Functions::identity())
            ->keys()
            ->first();
        // then
        $this->pass();
    }

    /**
     * @test
     */
    public function shouldMapEntriesSequential()
    {
        // given
        $stream = ArrayStream::of(['Neo', 'Morpheus', 'Trinity']);
        // when
        $stream->mapEntries(Functions::collectEntries($entries))->all();
        // then
        $expected = [
            [0, 'Neo'],
            [1, 'Morpheus'],
            [2, 'Trinity']
        ];
        $this->assertSame($expected, $entries);
    }

    /**
     * @test
     */
    public function shouldMapEntriesAssociative()
    {
        // given
        $stream = ArrayStream::of(['one' => 'Neo', 'two' => 'Morpheus', 'three' => 'Trinity']);
        // when
        $stream->mapEntries(Functions::collectEntries($entries))->all();
        // then
        $expected = [
            ['one', 'Neo'],
            ['two', 'Morpheus'],
            ['three', 'Trinity']
        ];
        $this->assertSame($expected, $entries);
    }

    /**
     * @test
     */
    public function shouldMapEntriesFirstSequential()
    {
        // given
        $stream = ArrayStream::of(['Neo', 'Morpheus', 'Trinity']);
        // when
        $stream->mapEntries(Functions::collectEntries($entries))->first();
        // then
        $this->assertSame([[0, 'Neo']], $entries);
    }

    /**
     * @test
     */
    public function shouldMapEntriesFirstAssociative()
    {
        // given
        $stream = ArrayStream::of(['one' => 'Neo', 'two' => 'Morpheus', 'three' => 'Trinity']);
        // when
        $stream->mapEntries(Functions::collectEntries($entries))->first();
        // then
        $this->assertSame([['one', 'Neo']], $entries);
    }

    /**
     * @test
     */
    public function shouldMapIdentity()
    {
        // given
        $input = ['one' => 'Neo', 'two' => 'Morpheus', 'three' => 'Trinity'];
        $stream = ArrayStream::of($input);
        // when
        $output = $stream->mapEntries(Functions::secondArgument())->all();
        // then
        $this->assertSame($input, $output);
    }

    /**
     * @test
     */
    public function shouldGetFirstKey()
    {
        // given
        $stream = ArrayStream::of(['foo' => 'bar']);
        // when
        $firstKey = $stream->mapEntries(Functions::identity())->keys()->first();
        // then
        $this->assertSame('foo', $firstKey);
    }

    /**
     * @test
     */
    public function shouldGetKeys()
    {
        // given
        $stream = ArrayStream::of(['one' => 'Neo', 'two' => 'Morpheus', 'three' => 'Trinity']);
        // when
        $firstKey = $stream->mapEntries(Functions::identity())->keys()->all();
        // then
        $this->assertSame(['one', 'two', 'three'], $firstKey);
    }
}

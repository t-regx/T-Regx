<?php
namespace Test\Feature\CleanRegex\Stream\map;

use PHPUnit\Framework\TestCase;
use Test\Utils\Functions;
use Test\Utils\Stream\ArrayStream;
use Test\Utils\TestCase\TestCasePasses;

/**
 * @covers \TRegx\CleanRegex\Match\Stream
 * @covers \TRegx\CleanRegex\Internal\Match\Stream\MapStream
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
        $mapped = $stream->map('strToUpper')->all();
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
        $mapped = $stream->map('strToUpper')->first();
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
        $stream->map(Functions::assertSame('One'))->first();
    }

    /**
     * @test
     */
    public function shouldInvokeMapper_forFirst_once()
    {
        // given
        $stream = ArrayStream::of(['One', 'Two', 'Three']);
        // when
        $stream->map(Functions::once())->first();
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
        $integers = $stream->map(Functions::toInt())->all();
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
        $integer = $stream->map(Functions::toInt())->first();
        // then
        $this->assertSame(10, $integer);
    }

    /**
     * @test
     */
    public function shouldInvokeMapper_forFirst_keys()
    {
        // given
        $stream = ArrayStream::of(['Neo', 'Trinity', 'Morpheus']);
        // when
        $stream->map(Functions::assertSame('Neo'))->keys()->first();
    }

    /**
     * @test
     */
    public function shouldInvokeMapper_forFirst_keys_once()
    {
        // given
        $stream = ArrayStream::of(['Neo', 'Trinity', 'Morpheus']);
        // when
        $stream->map(Functions::once())->keys()->first();
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
            ->map(Functions::identity())
            ->keys()
            ->first();
        // then
        $this->pass();
    }
}

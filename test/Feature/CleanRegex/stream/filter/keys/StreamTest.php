<?php
namespace Test\Feature\CleanRegex\stream\filter\keys;

use PHPUnit\Framework\TestCase;
use Test\Utils\Functions;
use Test\Utils\Stream\ArrayStream;

/**
 * @covers \TRegx\CleanRegex\Match\Stream
 * @covers \TRegx\CleanRegex\Internal\Match\Stream\FilterStream
 * @covers \TRegx\CleanRegex\Internal\Match\Stream\KeyStream
 */
class StreamTest extends TestCase
{
    /**
     * @test
     */
    public function shouldReturnFirstFilteredKey()
    {
        // given
        $stream = ArrayStream::of(['Foo', 'Bar']);
        // when
        $key = $stream->filter(Functions::equals('Foo'))->keys()->first();
        // then
        $this->assertSame(0, $key);
    }

    /**
     * @test
     */
    public function shouldReturnFirstFilteredKey_forAssociative()
    {
        // given
        $stream = ArrayStream::of(['f' => 'Foo', 'b' => 'Bar']);
        // when
        $key = $stream->filter(Functions::equals('Foo'))->keys()->first();
        // then
        $this->assertSame('f', $key);
    }

    /**
     * @test
     */
    public function shouldReturnFirstFilteredKey_forAssociative_integer()
    {
        // given
        $stream = ArrayStream::of(['Foo', 'Bar', 15 => 'Dor']);
        // when
        $key = $stream->filter(Functions::equals('Dor'))->keys()->first();
        // then
        $this->assertSame(15, $key);
    }

    /**
     * @test
     */
    public function shouldReturnFirstFilteredKeyOfKeys()
    {
        // given
        $stream = ArrayStream::of(['Foo', 'Bar', 'Door']);
        // when
        $key = $stream->filter(Functions::equals('Door'))->keys()->keys()->first();
        // then
        $this->assertSame(0, $key);
    }

    /**
     * @test
     */
    public function shouldReturnFirstFilteredKeyOfKeys_forAssociative()
    {
        // given
        $stream = ArrayStream::of(['f' => 'Foo', 'b' => 'Bar']);
        // when
        $key = $stream->filter(Functions::equals('Foo'))->keys()->keys()->first();
        // then
        $this->assertSame(0, $key);
    }

    /**
     * @test
     */
    public function shouldReturnFirstFilteredKey_nextKey()
    {
        // given
        $stream = ArrayStream::of(['Foo', 'Bar', 'Door']);
        // when
        $key = $stream->filter(Functions::equals('Door'))->keys()->first();
        // then
        $this->assertSame(2, $key);
    }

    /**
     * @test
     */
    public function shouldReturnFilteredEntries_forAssociative()
    {
        // given
        $stream = ArrayStream::of(['Foo' => 'Foo', 'Bar' => 'Bar', 'Dor' => 'Dor']);
        // when
        $entries = $stream->filter(Functions::equals('Dor'))->all();
        // then
        $this->assertSame(['Dor' => 'Dor'], $entries);
    }
}

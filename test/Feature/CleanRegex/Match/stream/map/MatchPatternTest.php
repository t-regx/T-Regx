<?php
namespace Test\Feature\CleanRegex\Match\stream\map;

use PHPUnit\Framework\TestCase;
use Test\Utils\DetailFunctions;
use Test\Utils\Functions;
use Test\Utils\TestCasePasses;
use TRegx\CleanRegex\Exception\NoSuchStreamElementException;
use TRegx\CleanRegex\Pattern;

/**
 * @covers \TRegx\CleanRegex\Internal\Match\Stream\MapStream
 */
class MatchPatternTest extends TestCase
{
    use TestCasePasses;

    /**
     * @test
     */
    public function shouldGetAll()
    {
        // when
        $mapped = Pattern::of('\w+')->match('One, Two, Three')
            ->stream()
            ->map('strToUpper')
            ->all();
        // then
        $this->assertSame(['ONE', 'TWO', 'THREE'], $mapped);
    }

    /**
     * @test
     */
    public function shouldGetFirst()
    {
        // when
        $mapped = Pattern::of('\w+')->match('One, Two, Three')
            ->stream()
            ->map('strToUpper')
            ->first();
        // then
        $this->assertSame('ONE', $mapped);
    }

    /**
     * @test
     */
    public function shouldNotCallMapperForMoreThanFirst()
    {
        // when
        Pattern::of('\w+')->match('One, Two, Three')
            ->stream()
            ->map(Functions::assertSame('One', DetailFunctions::text()))
            ->first();
    }

    /**
     * @test
     */
    public function shouldGetFirstInteger()
    {
        // when
        $mapped = Pattern::literal('15')->match('15')
            ->stream()
            ->map(DetailFunctions::toInt())
            ->first();
        // then
        $this->assertSame(15, $mapped);
    }

    /**
     * @test
     */
    public function shouldGetAllInteger()
    {
        // when
        $mapped = Pattern::of('\d+')->match('10, 15, 18')
            ->stream()
            ->map(DetailFunctions::toInt())
            ->all();
        // then
        $this->assertSame([10, 15, 18], $mapped);
    }

    /**
     * @test
     */
    public function shouldThrowForUnmatchedStream()
    {
        // given
        $stream = Pattern::literal('Foo')->match('Bar')->stream()->map(Functions::fail());
        // then
        $this->expectException(NoSuchStreamElementException::class);
        // when
        $stream->first();
    }

    /**
     * @test
     */
    public function shouldThrowForEmptyStream()
    {
        // given
        $stream = Pattern::of('Foo')->match('Foo')->stream()->flatMap(Functions::constant([]))->map(Functions::fail());
        // then
        $this->expectException(NoSuchStreamElementException::class);
        // when
        $stream->first();
    }

    /**
     * @test
     */
    public function shouldNotCallMapperForMoreThanFirstKey()
    {
        // when
        Pattern::of('\w+')->match('One, Two, Three')
            ->stream()
            ->map(Functions::assertSame('One', DetailFunctions::text()))
            ->keys()
            ->first();
    }

    /**
     * @test
     */
    public function shouldCallFlatMapOnce()
    {
        // when
        Pattern::of('Foo')
            ->match('Foo')
            ->stream()
            ->flatMap(Functions::once(['key']))
            ->map(Functions::identity())
            ->keys()
            ->first();
        // then
        $this->pass();
    }
}

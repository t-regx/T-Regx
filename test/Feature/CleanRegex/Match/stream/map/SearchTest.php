<?php
namespace Test\Feature\CleanRegex\Match\stream\map;

use PHPUnit\Framework\TestCase;
use Test\Utils\Functions;
use TRegx\CleanRegex\Exception\NoSuchStreamElementException;
use TRegx\CleanRegex\Pattern;

/**
 * @covers \TRegx\CleanRegex\Match\Search
 */
class SearchTest extends TestCase
{
    /**
     * @test
     */
    public function shouldThrowForUnmatchedStream()
    {
        // given
        $stream = Pattern::literal('Foo')->search('Bar')->stream()->map(Functions::fail());
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
        $match = Pattern::of('Foo')->search('Foo');
        $stream = $match->stream()->flatMap(Functions::constant([]))->map(Functions::fail());
        // then
        $this->expectException(NoSuchStreamElementException::class);
        // when
        $stream->first();
    }
}

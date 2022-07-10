<?php
namespace Test\Feature\CleanRegex\Match\stream\map;

use PHPUnit\Framework\TestCase;
use Test\Utils\Functions;
use TRegx\CleanRegex\Exception\NoSuchStreamElementException;
use TRegx\CleanRegex\Pattern;

/**
 * @covers \TRegx\CleanRegex\Match\Matcher
 */
class MatcherTest extends TestCase
{
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
        $matcher = Pattern::of('Foo')->match('Foo');
        $stream = $matcher->stream()->flatMap(Functions::constant([]))->map(Functions::fail());
        // then
        $this->expectException(NoSuchStreamElementException::class);
        // when
        $stream->first();
    }
}

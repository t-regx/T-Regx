<?php
namespace Test\Feature\CleanRegex\match\stream;

use PHPUnit\Framework\TestCase;
use Test\Utils\Assertion\AssertsDetail;
use TRegx\CleanRegex\Exception\NoSuchStreamElementException;
use TRegx\CleanRegex\Pattern;

/**
 * @covers \TRegx\CleanRegex\Match\Search
 */
class SearchTest extends TestCase
{
    use AssertsDetail;

    /**
     * @test
     */
    public function shouldGetStream()
    {
        // given
        $match = Pattern::of('\w+')->search('Gandalf the White');
        // when
        $stream = $match->stream();
        // then
        [$first, $second, $third] = $stream->all();
        $this->assertSame('Gandalf', $first);
        $this->assertSame('the', $second);
        $this->assertSame('White', $third);
    }

    /**
     * @test
     */
    public function shouldGetStreamFirst()
    {
        // given
        $match = Pattern::of('\w+')->search('Gandalf the White');
        // when
        $stream = $match->stream();
        // then
        $this->assertSame('Gandalf', $stream->first());
    }

    /**
     * @test
     */
    public function shouldGetStreamKey()
    {
        // given
        $match = Pattern::of('\w+')->search('Gandalf the White');
        // when
        $stream = $match->stream();
        // then
        $this->assertSame(0, $stream->keys()->first());
    }

    /**
     * @test
     */
    public function shouldThrowFirst_forUnmatchedSubject()
    {
        // given
        $stream = Pattern::of('I love you, Boromir')->search('Faramir')->stream();
        // then
        $this->expectException(NoSuchStreamElementException::class);
        $this->expectExceptionMessage('Expected to get the first match, but subject was not matched');
        // when
        $stream->first();
    }

    /**
     * @test
     */
    public function shouldCount_forUnmatchedSubject()
    {
        // given
        $stream = Pattern::of('Foo')->search('Bar')->stream();
        // when
        $count = $stream->count();
        // then
        $this->assertSame(0, $count);
    }

    /**
     * @test
     */
    public function shouldGetEmptyIterator_forUnmatchedSubject()
    {
        // given
        $stream = Pattern::of('I love you, Boromir')->search('Faramir')->stream();
        // when
        $iterator = $stream->getIterator();
        // then
        $this->assertSame([], \iterator_to_array($iterator));
    }

    /**
     * @test
     */
    public function shouldBeIterable()
    {
        // given
        $stream = pattern('\d+([cm]m)')->search('14cm, 12mm')->stream();
        // when
        [$first, $second] = \iterator_to_array($stream);
        // then
        $this->assertSame('14cm', $first);
        $this->assertSame('12mm', $second);
    }

    /**
     * @test
     */
    public function shouldGetAll_forUnmatchedSubject()
    {
        // given
        $stream = Pattern::of('I love you, Boromir')->search('Faramir')->stream();
        // when
        $all = $stream->all();
        // then
        $this->assertSame([], $all);
    }
}

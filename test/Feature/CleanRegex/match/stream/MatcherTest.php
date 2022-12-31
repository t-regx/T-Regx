<?php
namespace Test\Feature\CleanRegex\match\stream;

use PHPUnit\Framework\TestCase;
use Test\Utils\Assertion\AssertsDetail;
use TRegx\CleanRegex\Exception\NoSuchStreamElementException;
use TRegx\CleanRegex\Pattern;

/**
 * @covers \TRegx\CleanRegex\Match\Matcher
 */
class MatcherTest extends TestCase
{
    use AssertsDetail;

    /**
     * @test
     */
    public function shouldGetStream()
    {
        // given
        $matcher = Pattern::of('\w+')->match('Gandalf the White');
        // when
        $stream = $matcher->stream();
        // then
        [$first, $second, $third] = $stream->all();
        $this->assertSame('Gandalf', $first->text());
        $this->assertSame('the', $second->text());
        $this->assertSame('White', $third->text());
    }

    /**
     * @test
     */
    public function shouldGetStreamFirst()
    {
        // given
        $matcher = Pattern::of('\w+')->match('Gandalf the White');
        // when
        $stream = $matcher->stream();
        // then
        $first = $stream->first();
        $this->assertSame('Gandalf', $first->text());
    }

    /**
     * @test
     */
    public function shouldGetStreamKey()
    {
        // given
        $matcher = Pattern::of('\w+')->match('Gandalf the White');
        // when
        $stream = $matcher->stream();
        // then
        $this->assertSame(0, $stream->keys()->first());
    }

    /**
     * @test
     */
    public function shouldThrowFirst_forUnmatchedSubject()
    {
        // given
        $stream = Pattern::of('I love you, Boromir')->match('Faramir')->stream();
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
        $stream = Pattern::of('Foo')->match('Bar')->stream();
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
        $stream = Pattern::of('I love you, Boromir')->match('Faramir')->stream();
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
        $stream = pattern('\d+([cm]m)')->match('14cm, 12mm')->stream();
        // when
        [$first, $second] = \iterator_to_array($stream);
        // then
        $this->assertDetailText('14cm', $first);
        $this->assertDetailText('12mm', $second);
    }

    /**
     * @test
     */
    public function shouldGetAll_forUnmatchedSubject()
    {
        // given
        $stream = Pattern::of('I love you, Boromir')->match('Faramir')->stream();
        // when
        $all = $stream->all();
        // then
        $this->assertSame([], $all);
    }

    /**
     * @test
     */
    public function shouldKeepIndices()
    {
        // given
        $stream = pattern('Foo|Bar|Lorem')->match("Foo, Bar, Lorem")->stream();
        // when
        [$first, $second, $third] = $stream->all();
        // then
        $this->assertDetailIndex(0, $first);
        $this->assertDetailIndex(1, $second);
        $this->assertDetailIndex(2, $third);
    }

    /**
     * @test
     */
    public function shouldKeepIndex_first()
    {
        // given
        $detail = pattern('Foo|Bar')->match("Foo, Bar")->stream()->first();
        // then
        $this->assertDetailIndex(0, $detail);
    }

    /**
     * @test
     */
    public function shouldGetDetailAll_first()
    {
        // given
        $detail = pattern('Foo|Bar|Lorem')->match('Foo, Bar, Lorem')->stream()->first();
        // when
        $other = $detail->all();
        // then
        $this->assertSame(['Foo', 'Bar', 'Lorem'], $other);
    }

    /**
     * @test
     */
    public function shouldGetDetailAll()
    {
        // given
        $stream = pattern('Foo|Bar|Lorem')->match('Foo, Bar, Lorem')->stream();
        // when
        [$first, $second, $third] = $stream->all();
        // then
        $expected = ['Foo', 'Bar', 'Lorem'];
        $this->assertSame($expected, $first->all());
        $this->assertSame($expected, $second->all());
        $this->assertSame($expected, $third->all());
    }
}

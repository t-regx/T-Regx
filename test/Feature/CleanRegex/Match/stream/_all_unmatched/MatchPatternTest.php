<?php
namespace Test\Feature\TRegx\CleanRegex\Match\stream\_all_unmatched;

use PHPUnit\Framework\TestCase;
use Test\Utils\Functions;
use Test\Utils\TestCasePasses;
use TRegx\CleanRegex\Internal\Match\Stream\Base\UnmatchedStreamException;
use TRegx\CleanRegex\Pattern;

/**
 * @covers \TRegx\CleanRegex\Match\Stream
 */
class MatchPatternTest extends TestCase
{
    use TestCasePasses;

    /**
     * @test
     */
    public function shouldGet_all()
    {
        // given
        $stream = Pattern::of('Foo')->match('Bar');
        // when
        $all = $stream->all();
        // then
        $this->assertSame([], $all);
    }

    /**
     * @test
     */
    public function shouldGet_only()
    {
        // given
        $stream = Pattern::of('Foo')->match('Bar');
        // when
        $all = $stream->only(2);
        // then
        $this->assertSame([], $all);
    }

    /**
     * @test
     */
    public function shouldThrow_only_ForNegativeIndex()
    {
        // given
        $stream = Pattern::of('Foo')->match('Bar')->stream();
        // then
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Negative limit: -2');
        // when
        $stream->limit(-2);
    }

    /**
     * @test
     */
    public function shouldIgnore_forEach()
    {
        // given
        $stream = Pattern::of('Foo')->match('Bar');
        // when
        $stream->forEach(Functions::fail());
        // then
        $this->pass();
    }

    /**
     * @test
     */
    public function shouldPassThrough_forEach()
    {
        // given
        $stream = Pattern::of('Foo')->match('Foo')->stream();
        // then
        $this->expectException(UnmatchedStreamException::class);
        // when
        $stream->forEach(Functions::throws(new UnmatchedStreamException()));
    }

    /**
     * @test
     */
    public function shouldGet_count()
    {
        // given
        $stream = Pattern::of('Foo')->match('Bar');
        // when
        $count = $stream->count();
        // then
        $this->assertSame(0, $count);
    }

    /**
     * @test
     */
    public function shouldGet_getIterator()
    {
        // given
        $stream = Pattern::of('Foo')->match('Bar');
        // when
        $iterator = $stream->getIterator();
        // then
        $this->assertSame([], \iterator_to_array($iterator));
    }
}

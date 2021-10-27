<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Match\_all_unmatched;

use PHPUnit\Framework\TestCase;
use Test\Fakes\CleanRegex\Internal\Match\Stream\EmptyStream;
use Test\Fakes\CleanRegex\Internal\Match\Stream\ThrowStream;
use Test\Fakes\CleanRegex\Internal\Match\Stream\Upstream\AllStream;
use Test\Fakes\CleanRegex\Internal\ThrowSubject;
use Test\Utils\Functions;
use Test\Utils\TestCasePasses;
use TRegx\CleanRegex\Internal\Match\Stream\Base\UnmatchedStreamException;
use TRegx\CleanRegex\Match\Stream;

/**
 * @covers \TRegx\CleanRegex\Match\Stream
 */
class StreamTest extends TestCase
{
    use TestCasePasses;

    /**
     * @test
     */
    public function shouldGet_all()
    {
        // given
        $stream = new Stream(new EmptyStream(), new ThrowSubject());

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
        $stream = new Stream(new EmptyStream(), new ThrowSubject());

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
        $stream = new Stream(new ThrowStream(), new ThrowSubject());

        // then
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Negative limit: -2');

        // when
        $stream->only(-2);
    }

    /**
     * @test
     */
    public function shouldIgnore_forEach()
    {
        // given
        $stream = new Stream(new EmptyStream(), new ThrowSubject());

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
        $stream = new Stream(new AllStream(['value']), new ThrowSubject());

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
        $stream = new Stream(new EmptyStream(), new ThrowSubject());

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
        $stream = new Stream(new EmptyStream(), new ThrowSubject());

        // when
        $iterator = $stream->getIterator();

        // then
        $this->assertSame([], \iterator_to_array($iterator));
    }
}

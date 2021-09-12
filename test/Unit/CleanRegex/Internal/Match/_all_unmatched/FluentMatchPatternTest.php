<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Match\_all_unmatched;

use PHPUnit\Framework\TestCase;
use Test\Fakes\CleanRegex\Internal\Factory\Worker\ThrowingOptionalWorker;
use Test\Fakes\CleanRegex\Internal\Match\Stream\EmptyStream;
use Test\Fakes\CleanRegex\Internal\Match\Stream\ThrowStream;
use Test\Fakes\CleanRegex\Internal\Match\Stream\Upstream\AllStream;
use Test\Utils\Functions;
use Test\Utils\TestCasePasses;
use TRegx\CleanRegex\Internal\Match\Stream\Base\UnmatchedStreamException;
use TRegx\CleanRegex\Match\FluentMatchPattern;

/**
 * @covers \TRegx\CleanRegex\Match\FluentMatchPattern
 */
class FluentMatchPatternTest extends TestCase
{
    use TestCasePasses;

    /**
     * @test
     */
    public function shouldGet_all()
    {
        // given
        $pattern = new FluentMatchPattern(new EmptyStream(), ThrowingOptionalWorker::none());

        // when
        $all = $pattern->all();

        // then
        $this->assertSame([], $all);
    }

    /**
     * @test
     */
    public function shouldGet_only()
    {
        // given
        $pattern = new FluentMatchPattern(new EmptyStream(), ThrowingOptionalWorker::none());

        // when
        $all = $pattern->only(2);

        // then
        $this->assertSame([], $all);
    }

    /**
     * @test
     */
    public function shouldThrow_only_ForNegativeIndex()
    {
        // given
        $pattern = new FluentMatchPattern(new ThrowStream(), ThrowingOptionalWorker::none());

        // then
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Negative limit: -2');

        // when
        $pattern->only(-2);
    }

    /**
     * @test
     */
    public function shouldIgnore_forEach()
    {
        // given
        $pattern = new FluentMatchPattern(new EmptyStream(), ThrowingOptionalWorker::none());

        // when
        $pattern->forEach(Functions::fail());

        // then
        $this->pass();
    }

    /**
     * @test
     */
    public function shouldPassThrough_forEach()
    {
        // given
        $pattern = new FluentMatchPattern(new AllStream(['value']), ThrowingOptionalWorker::none());

        // then
        $this->expectException(UnmatchedStreamException::class);

        // when
        $pattern->forEach(Functions::throws(new UnmatchedStreamException()));
    }

    /**
     * @test
     */
    public function shouldGet_count()
    {
        // given
        $pattern = new FluentMatchPattern(new EmptyStream(), ThrowingOptionalWorker::none());

        // when
        $count = $pattern->count();

        // then
        $this->assertSame(0, $count);
    }

    /**
     * @test
     */
    public function shouldGet_getIterator()
    {
        // given
        $pattern = new FluentMatchPattern(new EmptyStream(), ThrowingOptionalWorker::none());

        // when
        $iterator = $pattern->getIterator();

        // then
        $this->assertSame([], iterator_to_array($iterator));
    }
}

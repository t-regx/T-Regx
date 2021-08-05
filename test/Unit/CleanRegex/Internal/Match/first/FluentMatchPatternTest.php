<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Match\first;

use PHPUnit\Framework\TestCase;
use Test\Utils\CustomException;
use Test\Utils\Functions;
use Test\Utils\Impl\EmptyStream;
use Test\Utils\Impl\FirstStream;
use Test\Utils\Impl\ThrowingOptionalWorker;
use TRegx\CleanRegex\Match\FluentMatchPattern;

/**
 * @covers \TRegx\CleanRegex\Match\FluentMatchPattern::first
 */
class FluentMatchPatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetFirst()
    {
        // given
        $pattern = new FluentMatchPattern(new FirstStream('foo'), ThrowingOptionalWorker::none());

        // when
        $result = $pattern->first();

        // then
        $this->assertSame('foo', $result);
    }

    /**
     * @test
     */
    public function shouldGetValuesFirst()
    {
        // given
        $pattern = new FluentMatchPattern(new FirstStream('foo'), ThrowingOptionalWorker::none());

        // when
        $result = $pattern->values()->first();

        // then
        $this->assertSame('foo', $result);
    }

    /**
     * @test
     */
    public function shouldInvoke_consumer()
    {
        // given
        $pattern = new FluentMatchPattern(new FirstStream('bar'), ThrowingOptionalWorker::none());

        // when
        $pattern->first(function ($value) {
            // then
            $this->assertSame('bar', $value);
        });
    }

    /**
     * @test
     */
    public function shouldThrowEmpty()
    {
        // given
        $pattern = new FluentMatchPattern(new EmptyStream(), ThrowingOptionalWorker::fluent(new CustomException('message')));

        // then
        $this->expectException(CustomException::class);
        $this->expectExceptionMessage('message');

        // when
        $pattern->first();
    }

    /**
     * @test
     */
    public function shouldThrowEmpty_consumer()
    {
        // given
        $pattern = new FluentMatchPattern(new EmptyStream(), ThrowingOptionalWorker::fluent(new CustomException('message')));

        // then
        $this->expectException(CustomException::class);
        $this->expectExceptionMessage('message');

        // when
        $pattern->first(Functions::fail());
    }
}

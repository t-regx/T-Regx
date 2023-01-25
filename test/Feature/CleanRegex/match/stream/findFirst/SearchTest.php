<?php
namespace Test\Feature\CleanRegex\match\stream\findFirst;

use PHPUnit\Framework\TestCase;
use Test\Utils\Assertion\AssertsOptional;
use Test\Utils\Classes\ExampleException;
use Test\Utils\Functions;
use TRegx\CleanRegex\Internal\Match\Stream\EmptyStreamException;
use TRegx\CleanRegex\Pattern;

/**
 * @covers \TRegx\CleanRegex\Match\Search
 */
class SearchTest extends TestCase
{
    use AssertsOptional;

    /**
     * @test
     */
    public function shouldFindFirstThrow_forUnmatchedSubject()
    {
        // given
        $stream = Pattern::of('Foo')->search('Bar')->stream();
        // when
        $optional = $stream->findFirst();
        // then
        $this->assertOptionalEmpty($optional);
    }

    /**
     * @test
     */
    public function shouldKeysFindFirstThrow_forUnmatchedSubject()
    {
        // given
        $stream = Pattern::of('Foo')->search('Bar')->stream();
        // when
        $optional = $stream->keys()->findFirst();
        // then
        $this->assertOptionalEmpty($optional);
    }

    /**
     * @test
     */
    public function shouldFindFirstThrow_forUnmatchedSubject_orThrow()
    {
        // given
        $stream = Pattern::of('Foo')->search('Bar')->stream();
        // then
        $this->expectException(ExampleException::class);
        // when
        $stream->findFirst()->orThrow(new ExampleException());
    }

    /**
     * @test
     */
    public function shouldFindFirstThrow_forEmptyStream()
    {
        // given
        $stream = Pattern::of('Foo')->search('Foo')->stream();
        // then
        $this->expectException(ExampleException::class);
        // when
        $stream
            ->filter(Functions::constant(false))
            ->findFirst()
            ->orThrow(new ExampleException());
    }

    /**
     * @test
     */
    public function shouldInvoke_orElse_withoutArguments_forUnmatchedSubject()
    {
        // given
        $stream = Pattern::of('Foo')->search('Bar')->stream();
        // when
        $stream->findFirst()->orElse(Functions::assertArgumentless());
    }

    /**
     * @test
     */
    public function shouldInvoke_orElse_withoutArguments_forEmptyStream()
    {
        // given
        $stream = Pattern::of('Foo')->search('Foo')->stream();
        // when
        $stream
            ->filter(Functions::constant(false))
            ->findFirst()
            ->orElse(Functions::assertArgumentless());
    }

    /**
     * @test
     */
    public function shouldFindFirstReturn_forUnmatchedSubject()
    {
        // given
        $stream = Pattern::of('Foo')->search('Bar')->stream();
        // when
        $value = $stream->findFirst()->orReturn('value');
        // then
        $this->assertSame('value', $value);
    }

    /**
     * @test
     */
    public function shouldPassThrough_InternalException()
    {
        // given
        $stream = Pattern::of('Foo')->search('Foo')->stream();
        try {
            // when
            $stream->findFirst()->map(Functions::throws(new EmptyStreamException()));
        } catch (EmptyStreamException $exception) {
            // then
            $this->assertEmpty($exception->getMessage());
        }
    }
}

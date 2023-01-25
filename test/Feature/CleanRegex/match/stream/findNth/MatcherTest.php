<?php
namespace Test\Feature\CleanRegex\match\stream\findNth;

use PHPUnit\Framework\TestCase;
use Test\Utils\Assertion\AssertsOptional;
use Test\Utils\Functions;
use TRegx\CleanRegex\Pattern;

/**
 * @covers \TRegx\CleanRegex\Match\Matcher
 * @covers \TRegx\CleanRegex\Match\Stream
 */
class MatcherTest extends TestCase
{
    use AssertsOptional;

    /**
     * @test
     */
    public function shouldThrow_findNth_forUnmatchedSubject()
    {
        // given
        $stream = Pattern::of('Foo')->match('Bar')->stream();
        // when
        $optional = $stream->findNth(5);
        // then
        $this->assertOptionalEmpty($optional);
    }

    /**
     * @test
     */
    public function shouldThrow_findNth_forStreamUnderflow()
    {
        // given
        $stream = Pattern::of('\d+')->match('12 13 14')->stream();
        // when
        $optional = $stream->findNth(5);
        // then
        $this->assertOptionalEmpty($optional);
    }

    /**
     * @test
     */
    public function shouldThrow_findNth_forEmptyStream()
    {
        // given
        $stream = Pattern::of('Foo')->match('Foo')->stream()->filter(Functions::constant(false));
        // when
        $optional = $stream->findNth(5);
        // then
        $this->assertOptionalEmpty($optional);
    }
}

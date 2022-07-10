<?php
namespace Test\Feature\CleanRegex\Match\stream\findNth;

use PHPUnit\Framework\TestCase;
use Test\Utils\Assertion\AssertsOptional;
use Test\Utils\Functions;

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
        $stream = pattern('Foo')->match('Bar')->stream();
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
        $stream = pattern('\d+')->match('12 13 14')->stream();
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
        $stream = pattern('Foo')->match('Foo')->stream()->filter(Functions::constant(false));
        // when
        $optional = $stream->findNth(5);
        // then
        $this->assertOptionalEmpty($optional);
    }
}
